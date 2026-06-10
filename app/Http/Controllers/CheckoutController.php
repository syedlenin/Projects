<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cartItems = Cart::with('product')
                        ->where('user_id', auth()->id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                             ->with('error', 'Your cart is empty.');
        }

        $subtotal       = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
        $shippingCharge = 60;  // Fixed BDT 60 for now
        $total          = $subtotal + $shippingCharge;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shippingCharge', 'total'));
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $request->validate([
            'shipping_name'    => ['required', 'string', 'max:100'],
            'shipping_phone'   => ['required', 'string', 'max:15'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city'    => ['required', 'string', 'max:100'],
            'payment_method'   => ['required', 'in:sslcommerz,aamarpay,shurjopay,cod'],
            'notes'            => ['nullable', 'string', 'max:500'],
        ]);

        $cartItems = Cart::with('product')
                        ->where('user_id', auth()->id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                             ->with('error', 'Your cart is empty.');
        }

        // Use DB transaction to ensure atomicity
        DB::beginTransaction();

        try {
            $subtotal       = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
            $shippingCharge = 60;
            $total          = $subtotal + $shippingCharge;

            // Create the order
            $order = Order::create([
                'user_id'          => auth()->id(),
                'order_number'     => Order::generateOrderNumber(),
                'subtotal'         => $subtotal,
                'shipping_charge'  => $shippingCharge,
                'total_amount'     => $total,
                'status'           => 'pending',
                'shipping_name'    => $request->shipping_name,
                'shipping_phone'   => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city'    => $request->shipping_city,
                'payment_method'   => $request->payment_method,
                'payment_status'   => 'unpaid',
                'notes'            => $request->notes,
            ]);

            // Create order items (snapshot product data)
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $cartItem->product_id,
                    'product_name'  => $cartItem->product->name,
                    'product_price' => $cartItem->product->price,
                    'quantity'      => $cartItem->quantity,
                    'subtotal'      => $cartItem->product->price * $cartItem->quantity,
                ]);

                // Decrement stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Clear the cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            // For COD, mark as processing immediately
            if ($request->payment_method === 'cod') {
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'unpaid'
                ]);

                return redirect()->route('orders.show', $order)
                                 ->with('success', 'Order placed successfully!');
            }

            // For online payment, redirect to payment initiation
       session(['order_id' => $order->id]);
return redirect()->route('payment.initiate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Order placement failed. Please try again.');
        }
    }
}
