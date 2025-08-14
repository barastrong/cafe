<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = ['Makanan', 'Minuman', 'Snack', 'Dessert'];
        
        foreach ($menu as $item) {
            DB::table('menus')->insert([
                'name' => $item,
                'slug' => strtolower(str_replace(' ', '-', $item)),
            ]);
        }
    }
}
