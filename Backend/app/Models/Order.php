<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'promo_id',
        'cart_id',
        'user_id',
        'customer_name',
        'quantity',
        'subtotal',
        'discount_amount',
        'grand_total',
        'status',
    ];

    protected $casts = [
        'promo_id' => 'integer',
        'cart_id' => 'integer',
        'user_id' => 'integer',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'status' => 'string',
    ];

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateGrandTotal()
    {
        $this->grand_total = $this->subtotal - $this->discount_amount;
        return $this->grand_total;
    }

    public function calculateDiscountAmount(Promo $promo)
    {
        $this->discount_amount = $promo->calculateDiscount($this->subtotal);
        return $this->discount_amount;
    }

    public function calculateSubtotal()
    {
        $this->subtotal = $this->quantity * $this->product->price;
        return $this->subtotal;
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
