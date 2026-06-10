<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;  // ← add this

    protected $fillable = [
        'name', 'description', 'price', 'stock',
        'image', 'category', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

public function getImageUrlAttribute(): string
{
    return $this->image
        ? asset('storage/' . $this->image)
        : 'https://picsum.photos/seed/' . $this->id . '/400/400';
}
}
