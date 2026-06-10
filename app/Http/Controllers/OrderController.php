<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::where('user_id', auth()->id())
                       ->latest()
                       ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        // Make sure user can only see their own orders
        abort_if($order->user_id !== auth()->id(), 403);

        return view('orders.show', compact('order'));
    }
}
