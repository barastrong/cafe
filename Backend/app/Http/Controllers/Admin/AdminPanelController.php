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
        $request->validate(['name' => 'required|string|max:255|unique:menus,name']);
        Menu::create(['name' => $request->name, 'slug' => Str::random(6)]);
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
            $productData['image'] = $request->file('image')->store('products', 'public');
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
        if ($conversation && $conversation->user_id !== Auth::id()) abort(403);
        return view('admin.chat', ['conversations' => $userConversations, 'activeConversation' => $conversation]);
    }

    public function chatStore(Request $request)
    {
        $request->validate(['prompt' => 'required|string|max:4000']);
        $apiKey = config('services.gemini.key');
        if (!$apiKey) return response()->json(['error' => 'Konfigurasi AI (API Key) belum lengkap.'], 500);

        $prompt = $request->input('prompt');
        $conversationId = $request->input('conversation_id');
        $conversation = $conversationId ? Conversation::findOrFail($conversationId) : Auth::user()->conversations()->create(['title' => Str::limit($prompt, 35)]);
        abort_if($conversation->user_id !== Auth::id(), 403);
        
        $history = $conversation->messages()->get()->map(function ($message) {
            $role = $message->role === 'user' ? 'user' : 'model';
            return ['role' => $role, 'parts' => [['text' => $message->content]]];
        })->values()->toArray();
        
        $currentMessageHistory = array_merge($history, [['role' => 'user', 'parts' => [['text' => $prompt]]]]);

        $tools = [['function_declarations' => [
            ['name' => 'get_products_from_database', 'description' => 'User (Admin) mau lihat data produk. Bisa diurutkan (orderBy), dibatasi (limit), difilter berdasarkan nama menu (menuName), harga minimum (min_price), atau harga maksimum (max_price).'],
            ['name' => 'get_menu_summary', 'description' => 'User (Admin) mau lihat ringkasan data menu beserta jumlah produk di dalamnya.'],
            ['name' => 'get_active_promos', 'description' => 'User (Admin) mau lihat promo-promo yang lagi aktif saat ini.'],
            ['name' => 'get_user_statistics', 'description' => 'User (Admin) mau lihat data statistik pengguna (total, admin, user biasa, user terbaru).'],
            ['name' => 'get_total_inventory_summary', 'description' => 'User (Admin) mau menghitung total inventaris: total stok semua produk, nilai totalnya, dan jumlah produk unik.'],
            ['name' => 'get_low_stock_products', 'description' => 'User (Admin) mau melihat produk apa saja yang stoknya mau habis. Threshold defaultnya adalah 10.'],
            ['name' => 'get_out_of_stock_products', 'description' => 'User (Admin) mau melihat daftar produk yang stoknya sudah habis (nol).'],
            ['name' => 'get_inventory_by_menu', 'description' => 'User (Admin) mau melihat rincian inventaris (jumlah stok dan nilai) yang dikelompokkan per menu.'],
            ['name' => 'get_expiring_promos', 'description' => 'User (Admin) mau tahu promo apa saja yang akan segera berakhir. Parameter default adalah 7 hari kedepan (days_until_expiry).'],
            ['name' => 'get_overall_summary', 'description' => 'User (Admin) meminta ringkasan atau gambaran umum kondisi bisnis saat ini (total produk, menu, user, promo, produk habis, produk menipis).'],
        ]]];
        
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=" . $apiKey;
        
        $systemInstruction = ['parts' => [['text' => "Anda adalah asisten AI yang efisien dan to-the-point di dalam panel admin.
- Fokus utama Anda adalah mengeksekusi perintah dan menyajikan data secara langsung.
- Ketika user meminta data, JANGAN memberikan respons pembuka seperti 'Oke, saya cek dulu ya'. Langsung panggil fungsi yang relevan.
- Setelah data (dalam bentuk tabel) siap, berikan judul atau pengantar yang sangat singkat. Contoh: 'Berikut data produk:', 'Statistik pengguna saat ini:'.
- Jika terjadi error atau data kosong, sampaikan informasi tersebut secara lugas dengan menyatakan bahwa data tidak ditemukan atau permintaan tidak dapat diproses.
- Selalu gunakan format Markdown (*tebal*, _miring_, - list) untuk keterbacaan.
- JANGAN PERNAH menampilkan JSON mentah kepada user."]]];

        $response = Http::post($apiUrl, [
            'contents' => $currentMessageHistory, 
            'tools' => $tools,
            'systemInstruction' => $systemInstruction
        ]);

        if ($response->status() === 429) {
            $retryAfter = $response->json('error.details.2.retryDelay', 'beberapa saat');
            $errorMessage = "Batas permintaan API (kuota gratis) telah tercapai. Silakan coba lagi setelah {$retryAfter} atau tunggu hingga kuota harian direset.";
            return response()->json(['error' => $errorMessage], 429);
        }

        if (!$response->successful()) {
            return response()->json(['error' => 'Gagal berkomunikasi dengan AI. ' . $response->body()], 500);
        }
        
        $conversation->messages()->create(['role' => 'user', 'content' => $prompt]);
        
        $aiResponsePart = $response->json('candidates.0.content.parts.0');

        if (isset($aiResponsePart['functionCall'])) {
            $functionName = $aiResponsePart['functionCall']['name'];
            $args = $aiResponsePart['functionCall']['args'] ?? [];
            
            \Illuminate\Support\Facades\Log::info('AI Function Call:', ['function' => $functionName, 'arguments' => $args]);

            $toolService = new AiToolService();
            if (!method_exists($toolService, $functionName)) {
                $errorMessage = "Error Internal: Fungsi '{$functionName}' tidak ditemukan di server.";
                $conversation->messages()->create(['role' => 'assistant', 'content' => $errorMessage]);
                return response()->json(['reply' => $errorMessage, 'conversation_id' => $conversation->id]);
            }

            $functionResult = call_user_func_array([$toolService, $functionName], $args);

            if (empty($functionResult)) {
                $finalResponse = 'Tidak ada data yang ditemukan untuk permintaan ini.';
                $conversation->messages()->create(['role' => 'assistant', 'content' => $finalResponse]);
                return response()->json(['reply' => $finalResponse, 'conversation_id' => $conversation->id]);
            }
            
            $htmlTable = $this->generateHtmlTableFromArray($functionResult);
            
            $validFunctionCall = ['name' => $functionName, 'args' => (object)$args];
            $historyForSecondCall = array_merge($currentMessageHistory, [
                ['role' => 'model', 'parts' => [['functionCall' => $validFunctionCall]]],
                ['role' => 'function', 'parts' => [['functionResponse' => ['name' => $functionName, 'response' => ['content' => json_encode($functionResult)]]]]]
            ]);

            $responseAfterFunctionCall = Http::post($apiUrl, [
                'contents' => $historyForSecondCall,
                'tools' => $tools,
                'systemInstruction' => $systemInstruction
            ]);

            if (!$responseAfterFunctionCall->successful()) {
                if ($responseAfterFunctionCall->status() === 429) {
                    $retryAfter = $responseAfterFunctionCall->json('error.details.2.retryDelay', 'beberapa saat');
                    $errorMessage = "Batas permintaan API (kuota gratis) telah tercapai pada panggilan kedua. Silakan coba lagi setelah {$retryAfter}.";
                    return response()->json(['error' => $errorMessage], 429);
                }
                return response()->json(['error' => 'Terjadi kegagalan pada panggilan kedua ke AI. ' . $responseAfterFunctionCall->body()], 500);
            }

            $finalTextResponse = $responseAfterFunctionCall->json('candidates.0.content.parts.0.text', 'Berikut adalah data yang diminta.');
            $finalResponse = $htmlTable . $finalTextResponse;

        } else {
            $finalResponse = $aiResponsePart['text'] ?? 'Maaf, saya tidak dapat memproses permintaan Anda. Silakan coba lagi.';
        }

        $conversation->messages()->create(['role' => 'assistant', 'content' => $finalResponse]);
        return response()->json(['reply' => $finalResponse, 'conversation_id' => $conversation->id]);
    }

    public function chatDestroy(Conversation $conversation)
    {
        abort_if($conversation->user_id !== Auth::id(), 403, 'Unauthorized action.');
        $conversation->delete();
        return redirect()->route('admin.chat')->with('success', 'Percakapan telah dihapus.');
    }

    private function generateHtmlTableFromArray(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        $isListOfItems = isset($data[0]) && is_array($data[0]);
        $items = $isListOfItems ? $data : [$data];
        
        $html = '<div class="overflow-x-auto relative shadow-md sm:rounded-lg my-4"><table class="w-full text-sm text-left text-slate-300">';
        $headers = array_keys($items[0]);
        $html .= '<thead class="text-xs text-slate-200 uppercase bg-slate-800"><tr>';
        foreach ($headers as $header) {
            $html .= '<th scope="col" class="py-3 px-6">' . htmlspecialchars(str_replace('_', ' ', Str::ucfirst($header))) . '</th>';
        }
        $html .= '</tr></thead><tbody>';

        foreach ($items as $row) {
            $html .= '<tr class="bg-slate-700 border-b border-slate-600 hover:bg-slate-600">';
            foreach ($headers as $header) {
                $cellData = $row[$header] ?? '';
                if (is_array($cellData)) {
                    $cellData = $cellData['name'] ?? '(Complex Data)';
                } elseif (is_bool($cellData)) {
                    $cellData = $cellData ? 'Ya' : 'Tidak';
                }
                $html .= '<td class="py-4 px-6">' . htmlspecialchars((string)$cellData) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table></div>';
        return $html;
    }
}