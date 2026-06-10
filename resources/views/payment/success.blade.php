@extends('layouts.app')
@section('title', 'Payment Successful')

@section('content')
<div class="container-xl">
    <div style="max-width:520px;margin:3rem auto;">
        <div class="card-clean text-center p-5">
            <div style="width:80px;height:80px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <i class="bi bi-check-lg" style="font-size:2.2rem;color:#065f46;"></i>
            </div>

            <h2 style="font-family:'Sora',sans-serif;font-weight:700;font-size:1.6rem;margin-bottom:0.5rem;">Payment Successful!</h2>
            <p class="text-muted mb-3">Your order has been placed and payment confirmed.</p>

            @if(isset($orderNumber))
                <div style="background:var(--surface);border-radius:10px;padding:1rem;margin-bottom:1.5rem;">
                    <div style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.8px;color:var(--text-muted);margin-bottom:0.25rem;font-weight:600;">Order Number</div>
                    <div style="font-family:'Sora',sans-serif;font-size:1.2rem;font-weight:700;color:var(--primary);">{{ $orderNumber }}</div>
                </div>
            @endif

            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="{{ route('orders.index') }}" class="btn btn-accent" style="background:var(--primary);color:#fff;border:none;border-radius:9px;font-weight:600;padding:0.6rem 1.4rem;font-family:'Sora',sans-serif;">
                    <i class="bi bi-receipt me-2"></i>View Orders
                </a>
                <a href="{{ route('home') }}" class="btn" style="border:1.5px solid var(--border);border-radius:9px;font-weight:600;padding:0.6rem 1.4rem;color:var(--text-main);font-family:'Sora',sans-serif;">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
