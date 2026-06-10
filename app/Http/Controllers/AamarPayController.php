<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

class AamarPayController extends Controller
{
    private string $storeId;
    private string $signatureKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->storeId      = config('services.aamarpay.store_id');
        $this->signatureKey = config('services.aamarpay.signature_key');
        $this->baseUrl      = config('services.aamarpay.mode') === 'live'
            ? config('services.aamarpay.live_url')
            : config('services.aamarpay.sandbox_url');
    }

   public function initiate(Order $order): mixed
{
    $transactionId = $order->order_number;

    $postData = [
        'store_id'       => $this->storeId,
        'tran_id'        => $transactionId,
        'success_url'    => route('aamarpay.success'),
        'fail_url'       => route('aamarpay.fail'),
        'cancel_url'     => route('aamarpay.cancel'),
        'amount'         => $order->total_amount,
        'currency'       => 'BDT',
        'signature_key'  => $this->signatureKey,
        'desc'           => 'Order #' . $order->order_number,
        'cus_name'       => $order->shipping_name,
        'cus_email'      => $order->user->email,
        'cus_phone'      => $order->shipping_phone,
        'cus_add1'       => $order->shipping_address,
        'cus_city'       => $order->shipping_city,
        'cus_country'    => 'Bangladesh',
        'type'           => 'json',
    ];

    $response = Http::asForm()
                    ->post($this->baseUrl . '/request.php', $postData)
                    ->json();

    // TEMPORARY DEBUG — remove after fixing
    dd($response);

        Payment::create([
            'order_id'         => $order->id,
            'user_id'          => $order->user_id,
            'payment_method'   => 'aamarpay',
            'gateway_order_id' => $transactionId,
            'amount'           => $order->total_amount,
            'status'           => 'pending',
            'gateway_response' => $response,
        ]);

        if (isset($response['payment_url'])) {
            return redirect($response['payment_url']);
        }

        return redirect()->route('orders.show', $order)
                         ->with('error', 'aamarPay initiation failed. Please try again.');
    }

    public function success(Request $request): RedirectResponse
    {
        $merTxnId = $request->mer_txnid;

        $verification = Http::get($this->baseUrl . '/api/v1/trxcheck/request.php', [
            'request_id'    => $merTxnId,
            'store_id'      => $this->storeId,
            'signature_key' => $this->signatureKey,
            'type'          => 'json',
        ])->json();

        $order = Order::where('order_number', $merTxnId)->firstOrFail();
        $payment = Payment::where('order_id', $order->id)->latest()->first();

        if (isset($verification['pay_status']) && $verification['pay_status'] === 'Successful') {
            $payment->update([
                'transaction_id'   => $verification['pg_txnid'] ?? null,
                'status'           => 'success',
                'gateway_response' => $verification,
                'paid_at'          => now(),
            ]);

            $order->update([
                'payment_status' => 'paid',
                'status'         => 'processing',
            ]);

            return redirect()->route('payment.success', $order->order_number)
                             ->with('success', 'Payment successful!');
        }

        $payment->update([
            'status'           => 'failed',
            'gateway_response' => $verification,
        ]);

        return redirect()->route('payment.fail')
                         ->with('error', 'aamarPay payment failed.');
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
