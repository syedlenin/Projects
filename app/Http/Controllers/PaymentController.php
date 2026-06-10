<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\SSLCommerzController;
use App\Http\Controllers\AamarPayController;
use App\Http\Controllers\ShurjoPayController;

class PaymentController extends Controller
{
    public function initiate(Request $request)
    {
        $order = Order::findOrFail($request->session()->get('order_id'));

        return match($order->payment_method) {
            'sslcommerz' => app(SSLCommerzController::class)->initiate($order),
            'aamarpay'   => app(AamarPayController::class)->initiate($order),
            'shurjopay'  => app(ShurjoPayController::class)->initiate($order),
            default      => redirect()->route('orders.show', $order)
                                      ->with('error', 'Invalid payment method.'),
        };
    }
}
