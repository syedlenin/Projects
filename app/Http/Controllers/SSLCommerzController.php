<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class SSLCommerzController extends Controller
{
    private string $storeId;
    private string $storePassword;
    private string $baseUrl;

    public function __construct()
    {
        $this->storeId       = config('services.sslcommerz.store_id');
        $this->storePassword = config('services.sslcommerz.store_password');
        $this->baseUrl       = config('services.sslcommerz.mode') === 'live'
            ? config('services.sslcommerz.live_url')
            : config('services.sslcommerz.sandbox_url');
    }

    public function initiate(Order $order): mixed
    {
        $postData = [
            'store_id'         => $this->storeId,
            'store_passwd'     => $this->storePassword,
            'total_amount'     => $order->total_amount,
            'currency'         => 'BDT',
            'tran_id'          => $order->order_number,
            'success_url'      => route('sslcommerz.success'),
            'fail_url'         => route('sslcommerz.fail'),
            'cancel_url'       => route('sslcommerz.cancel'),
            'cus_name'         => $order->shipping_name,
            'cus_email'        => $order->user->email,
            'cus_add1'         => $order->shipping_address,
            'cus_city'         => $order->shipping_city,
            'cus_country'      => 'Bangladesh',
            'cus_phone'        => $order->shipping_phone,
            'ship_name'        => $order->shipping_name,
            'ship_add1'        => $order->shipping_address,
            'ship_city'        => $order->shipping_city,
            'ship_country'     => 'Bangladesh',
            'product_name'     => 'Order #' . $order->order_number,
            'product_category' => 'Mixed',
            'product_profile'  => 'general',
        ];

        $response = Http::asForm()
                        ->post($this->baseUrl . '/gwprocess/v4/api.php', $postData);

        $result = $response->json();

        Payment::create([
            'order_id'         => $order->id,
            'user_id'          => $order->user_id,
            'payment_method'   => 'sslcommerz',
            'gateway_order_id' => $order->order_number,
            'amount'           => $order->total_amount,
            'status'           => 'pending',
            'gateway_response' => $result,
        ]);

        if (isset($result['GatewayPageURL']) && $result['status'] === 'SUCCESS') {
            return redirect($result['GatewayPageURL']);
        }

        return redirect()->route('orders.show', $order)
                         ->with('error', 'Payment initiation failed. Please try again.');
    }

    public function success(Request $request): RedirectResponse
    {
        $validationUrl = $this->baseUrl . '/validator/api/validationserverAPI.php';
        $validation = Http::get($validationUrl, [
            'val_id'       => $request->val_id,
            'store_id'     => $this->storeId,
            'store_passwd' => $this->storePassword,
            'format'       => 'json',
        ])->json();

        if ($validation['status'] !== 'VALID' && $validation['status'] !== 'VALIDATED') {
            return redirect()->route('payment.fail')
                             ->with('error', 'Payment validation failed.');
        }

        $order = Order::where('order_number', $request->tran_id)->firstOrFail();

        Payment::where('order_id', $order->id)->update([
            'transaction_id'   => $request->bank_tran_id,
            'status'           => 'success',
            'gateway_response' => $validation,
            'paid_at'          => now(),
        ]);

        $order->update([
            'payment_status' => 'paid',
            'status'         => 'processing',
        ]);

        return redirect()->route('payment.success', $order->order_number)
                         ->with('success', 'Payment successful!');
    }

    public function fail(Request $request): RedirectResponse
    {
        return redirect()->route('payment.fail')
                         ->with('error', 'Payment failed. Please try again.');
    }

    public function cancel(Request $request): RedirectResponse
    {
        return redirect()->route('payment.cancel')
                         ->with('info', 'Payment cancelled.');
    }
}
