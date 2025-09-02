<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Product;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AiToolService
{
    public function get_products_from_database($orderBy = 'created_at', $orderDirection = 'desc', $limit = 10, $menuName = null, $min_price = null, $max_price = null)
    {
        $query = Product::with('menu:id,name');

        if ($menuName) {
            $query->whereHas('menu', function ($q) use ($menuName) {
                $q->where('name', 'like', '%' . $menuName . '%');
            });
        }
        
        if (!is_null($min_price)) {
            $query->where('price', '>=', $min_price);
        }

        if (!is_null($max_price)) {
            $query->where('price', '<=', $max_price);
        }

        return $query->orderBy($orderBy, $orderDirection)
                     ->limit($limit)
                     ->get(['name', 'price', 'stock', 'menu_id'])
                     ->toArray();
    }

    public function get_menu_summary()
    {
        return Menu::withCount('products')->get(['id', 'name'])->map(function ($menu) {
            return [
                'nama_menu' => $menu->name,
                'jumlah_produk' => $menu->products_count,
            ];
        })->toArray();
    }

    public function get_active_promos()
    {
        return Promo::where('is_active', true)
                     ->whereDate('start_date', '<=', now())
                     ->whereDate('end_date', '>=', now())
                     ->get()->map(function ($promo) {
                        return [
                            'kode' => $promo->code,
                            'tipe' => $promo->type,
                            'nilai' => $promo->value,
                            'berakhir_pada' => $promo->end_date->format('d M Y'),
                        ];
                    })->toArray();
    }

    public function get_user_statistics()
    {
        $latestUser = User::latest()->first();
        return [
            'total_pengguna' => User::count(),
            'total_admin' => User::where('is_admin', true)->count(),
            'total_pengguna_biasa' => User::where('is_admin', false)->count(),
            'pengguna_terbaru' => $latestUser->name ?? 'N/A',
            'terakhir_terdaftar' => $latestUser ? $latestUser->created_at->format('d M Y') : 'N/A',
        ];
    }

    public function get_total_inventory_summary()
    {
        return [
            'total_unit_produk' => (int) Product::sum('stock'),
            'estimasi_nilai_inventaris' => (float) Product::sum(DB::raw('price * stock')),
            'produk_unik' => Product::count(),
        ];
    }

    public function get_low_stock_products($threshold = 10)
    {
        return Product::where('stock', '<', $threshold)
                       ->where('stock', '>', 0)
                       ->orderBy('stock', 'asc')
                       ->get(['name', 'stock'])
                       ->toArray();
    }

    public function get_out_of_stock_products()
    {
        return Product::where('stock', '=', 0)->get(['name', 'price'])->toArray();
    }
    
    public function get_inventory_by_menu()
    {
        return Menu::withCount('products')
            ->withSum('products as total_stock', 'stock')
            ->withSum('products as total_value', DB::raw('price * stock'))
            ->get()->map(function ($menu) {
                return [
                    'nama_menu' => $menu->name,
                    'jumlah_produk' => $menu->products_count,
                    'total_stok_di_menu' => (int) $menu->total_stock,
                    'estimasi_nilai_di_menu' => (float) $menu->total_value,
                ];
            })->toArray();
    }

    public function get_expiring_promos($days_until_expiry = 7)
    {
        $targetDate = now()->addDays($days_until_expiry);
        return Promo::where('is_active', true)
                       ->whereDate('end_date', '>=', now())
                       ->whereDate('end_date', '<=', $targetDate)
                       ->orderBy('end_date', 'asc')
                       ->get()->map(function ($promo) {
                            return [
                                'kode' => $promo->code,
                                'berakhir_dalam_hari' => now()->diffInDays($promo->end_date, false),
                                'tanggal_berakhir' => $promo->end_date->format('d M Y'),
                            ];
                        })->toArray();
    }

    public function get_overall_summary()
    {
        return [
            'total_produk' => Product::count(),
            'total_menu' => Menu::count(),
            'pengguna_terdaftar' => User::count(),
            'promo_aktif' => Promo::where('is_active', true)->whereDate('start_date', '<=', now())->whereDate('end_date', '>=', now())->count(),
            'produk_habis' => Product::where('stock', 0)->count(),
            'produk_menipis' => Product::where('stock', '<', 10)->where('stock', '>', 0)->count(),
        ];
    }
}