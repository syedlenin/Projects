<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// app/Models/Cart.php
class Cart extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    // Calculate subtotal for this cart item
    public function getSubtotalAttribute(): float
    {
        return $this->product->price * $this->quantity;
    }
}
