<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'menu_id',
        'name',
        'slug',
        'image',
        'price',
        'stock',
    ];
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
