<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Product;
use App\Models\Promo;
use App\Models\User;

class AiToolService
{
    /**
     *
     * @param string $orderBy Kolom untuk pengurutan (contoh: 'price', 'name'). Default: 'created_at'.
     * @param string $orderDirection Arah pengurutan ('asc' atau 'desc'). Default: 'desc'.
     * @param int $limit Jumlah produk yang akan diambil. Default: 10.
     * @return string
     */
    public function get_products_from_database($orderBy = 'created_at', $orderDirection = 'desc', $limit = 10)
    {
        $products = Product::with('menu:id,name')
                           ->orderBy($orderBy, $orderDirection)
                           ->limit($limit)
                           ->get(['name', 'price', 'stock', 'menu_id']);

        return json_encode($products->toArray());
    }

    /**
     *
     * @return string
     */
    public function get_menu_summary()
    {
        $summary = Menu::withCount('products')->get(['id', 'name', 'products_count']);
        return json_encode($summary->toArray());
    }

    /**
     *
     * @return string
     */
    public function get_active_promos()
    {
        $now = now();
        $promos = Promo::where('is_active', true)
                       ->where('start_date', '<=', $now)
                       ->where('end_date', '>=', $now)
                       ->get(['code', 'type', 'value', 'end_date']);

        if ($promos->isEmpty()) {
            return json_encode(['message' => 'Saat ini tidak ada promo yang aktif.']);
        }

        return json_encode($promos->toArray());
    }

    /**
     *
     * @return string
     */
    public function get_user_statistics()
    {
        $adminCount = User::where('is_admin', true)->count();
        $regularUserCount = User::where('is_admin', false)->count();

        $stats = [
            'total_users' => User::count(),
            'admin_users' => $adminCount,
            'regular_users' => $regularUserCount,
        ];
        return json_encode($stats);
    }
}