<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;


// app/Http/Controllers/Admin/DashboardController.php
class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'total_revenue'   => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_products'  => Product::count(),
            'total_users'     => User::where('role', 'customer')->count(),
        ];
        // Recent orders
        $recentOrders = Order::with('user')
                             ->latest()
                             ->take(10)
                             ->get();
        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
