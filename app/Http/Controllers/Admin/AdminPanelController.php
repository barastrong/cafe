<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;
use App\Models\Product;
use App\Models\Menu;
use App\Models\User;
use App\Models\Promo;
use Illuminate\Support\Str;
use App\Services\AiToolService;

class AdminPanelController extends Controller
{

    public function index()
    {
        $productCount = Product::count();
        $userCount = User::count(); 

        return view('admin.index', compact('productCount', 'userCount'));
    }

    public function menusIndex()
    {
        $menus = Menu::latest()->paginate(10); 
        return view('admin.menus', compact('menus'));
    }

    public function menusStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menus,name',
        ]);

        $randomPart =str::random(6);

        Menu::create([
            'name' => $request->name,
            'slug' => $randomPart
        ]);


        return redirect()->back()->with('success', 'Menu created successfully.');
    }

    public function productsIndex()
    {
        $products = Product::with('menu')->latest()->paginate(10);
        $menus = Menu::all();
        return view('admin.products', compact('products', 'menus'));
    }

    public function productsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'menu_id' => 'required|exists:menus,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $productData = $request->except('image');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $productData['image'] = $imagePath;
        }

        Product::create($productData);

        return redirect()->back()->with('success', 'Product created successfully.');
    }

    public function promosIndex()
    {
        $promos = Promo::latest()->paginate(10);
        return view('admin.promos', compact('promos'));
    }

    public function promosStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promos,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
        ]);

        $promoData = $request->all();
        $promoData['is_active'] = $request->has('is_active');

        Promo::create($promoData);

        return redirect()->back()->with('success', 'Promo created successfully.');
    }

    public function chatIndex(Conversation $conversation = null)
    {
        $userConversations = Auth::user()->conversations()->latest()->get();

        if ($conversation && $conversation->user_id !== Auth::id()) {
            abort(403);
        }

        return view('admin.chat', [
            'conversations' => $userConversations,
            'activeConversation' => $conversation
        ]);
    }

    public function chatStore(Request $request)
    {
        $request->validate(['prompt' => 'required|string|max:4000']);
        $apiKey = config('services.gemini.key');
        if (!$apiKey) return response()->json(['error' => 'AI Service is not configured.'], 500);

        $prompt = $request->input('prompt');
        $conversationId = $request->input('conversation_id');
        $conversation = $conversationId ? Conversation::findOrFail($conversationId) : Auth::user()->conversations()->create(['title' => substr($prompt, 0, 35) . '...']);
        abort_if($conversation->user_id !== Auth::id(), 403);
        $conversation->messages()->create(['role' => 'user', 'content' => $prompt]);

        $tools = [
            [
                'function_declarations' => [
                    ['name' => 'get_products_from_database', 'description' => 'Mengambil daftar produk dari database kafe.', 'parameters' => ['type' => 'OBJECT', 'properties' => ['orderBy' => ['type' => 'STRING'], 'orderDirection' => ['type' => 'STRING'], 'limit' => ['type' => 'NUMBER']]]],
                    ['name' => 'get_menu_summary', 'description' => 'Mengambil ringkasan semua kategori menu dan jumlah produk di dalamnya.'],
                    ['name' => 'get_active_promos', 'description' => 'Mengambil daftar kode promo yang sedang aktif saat ini.'],
                    ['name' => 'get_user_statistics', 'description' => 'Mengambil data statistik pengguna, seperti jumlah total pengguna.'], // Deskripsi disesuaikan
                ]
            ]
        ];

        // Dapatkan riwayat chat untuk konteks
        $history = $conversation->messages()->get()->map(function ($msg) {
            return ['role' => $msg->role === 'assistant' ? 'model' : 'user', 'parts' => [['text' => $msg->content]]];
        })->values()->toArray();
        
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;
        $response = Http::post($apiUrl, ['contents' => $history, 'tools' => $tools]);

        if (!$response->successful()) return response()->json(['error' => 'Failed to communicate with AI. ' . $response->body()], 500);
        
        $aiResponsePart = $response->json('candidates.0.content.parts.0');

        if (isset($aiResponsePart['functionCall'])) {
            $functionCall = $aiResponsePart['functionCall'];
            $functionName = $functionCall['name'];
            $args = $functionCall['args'] ?? [];

            $toolService = new AiToolService();
            $functionResult = '';

            if (method_exists($toolService, $functionName)) {
                $functionResult = call_user_func_array([$toolService, $functionName], $args);
            } else {
                $functionResult = json_encode(['status' => 'error', 'message' => "Fungsi '{$functionName}' tidak ditemukan."]);
            }
            
            $responseAfterFunctionCall = Http::post($apiUrl, [
                'contents' => array_merge($history, [
                    ['role' => 'model', 'parts' => [['functionCall' => $functionCall]]],
                    ['role' => 'function', 'parts' => [['functionResponse' => ['name' => $functionName, 'response' => ['content' => $functionResult]]]]]
                ]),
                'tools' => $tools,
            ]);

            if (!$responseAfterFunctionCall->successful()) {
                 return response()->json(['error' => 'Failed on second AI call. ' . $responseAfterFunctionCall->body()], 500);
            }

            $finalResponse = $responseAfterFunctionCall->json('candidates.0.content.parts.0.text', 'Saya telah memproses data, tetapi gagal merangkumnya.');

        } else {
            $finalResponse = $aiResponsePart['text'] ?? 'Maaf, terjadi kesalahan saat memproses jawaban.';
        }

        $conversation->messages()->create(['role' => 'assistant', 'content' => $finalResponse]);
        return response()->json(['reply' => $finalResponse, 'conversation_id' => $conversation->id]);
    }
}