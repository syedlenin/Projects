@extends('layouts.app')
@section('title', 'Payment Cancelled')

@section('content')
<div class="container-xl">
    <div style="max-width:520px;margin:3rem auto;">
        <div class="card-clean text-center p-5">
            <div style="width:80px;height:80px;background:#fef3c7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <i class="bi bi-dash-circle" style="font-size:2.2rem;color:#92400e;"></i>
            </div>

            <h2 style="font-family:'Sora',sans-serif;font-weight:700;font-size:1.6rem;margin-bottom:0.5rem;">Payment Cancelled</h2>
            <p class="text-muted mb-3">You cancelled the payment. Your order is saved — you can pay anytime.</p>

            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="{{ route('orders.index') }}" class="btn" style="background:var(--primary);color:#fff;border:none;border-radius:9px;font-weight:600;padding:0.6rem 1.4rem;font-family:'Sora',sans-serif;">
                    <i class="bi bi-receipt me-2"></i>My Orders
                </a>
                <a href="{{ route('home') }}" class="btn" style="border:1.5px solid var(--border);border-radius:9px;font-weight:600;padding:0.6rem 1.4rem;color:var(--text-main);font-family:'Sora',sans-serif;">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
