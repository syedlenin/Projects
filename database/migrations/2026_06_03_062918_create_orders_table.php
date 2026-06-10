<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('order_number')->unique();    // e.g. ORD-20241201-001
        $table->decimal('subtotal', 10, 2);
        $table->decimal('shipping_charge', 10, 2)->default(0);
        $table->decimal('total_amount', 10, 2);
        $table->string('status')->default('pending');
        // status options: pending | processing | shipped | delivered |cancelled
        // Shipping address (denormalized for record keeping)
        $table->string('shipping_name');
        $table->string('shipping_phone');
        $table->text('shipping_address');
        $table->string('shipping_city');
        // Payment info
        $table->string('payment_method')->nullable();
        // payment_method: sslcommerz | aamarpay | shurjopay | cod
        $table->string('payment_status')->default('unpaid');
        // payment_status: unpaid | paid | failed | refunded
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
