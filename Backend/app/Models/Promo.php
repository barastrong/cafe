<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;
    protected $table = 'promos';

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase',
        'start_date',
        'end_date',
        'is_active',
        'image',
    ];


    protected $casts = [
        'value'         => 'float',
        'min_purchase'  => 'float',
        'start_date'    => 'datetime',
        'end_date'      => 'datetime',
        'is_active'     => 'boolean',
    ];

    /**
     *
     * @param float $subtotal
     * @return float
     */
    public function calculateDiscount(float $subtotal): float
    {
        $discount = 0;

        if ($this->type === 'percent') {
            $discount = ($this->value / 100) * $subtotal;
        } 
        elseif ($this->type === 'fixed') {
            $discount = $this->value;
        }

        return min($discount, $subtotal);
    }
}