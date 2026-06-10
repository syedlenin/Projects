<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// app/Models/Payment.php
class Payment extends Model
{
    protected $fillable = [
        'order_id', 'user_id', 'payment_method', 'transaction_id',
        'gateway_order_id', 'amount', 'currency', 'status',
        'gateway_response', 'paid_at'
    ];
    protected $casts = [
        'gateway_response' => 'array',
        'paid_at'          => 'datetime',
        'amount'           => 'decimal:2',
    ];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
