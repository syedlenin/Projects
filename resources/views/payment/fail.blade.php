@extends('layouts.app')
@section('title', 'Payment Failed')

@section('content')
<div class="container-xl">
    <div style="max-width:520px;margin:3rem auto;">
        <div class="card-clean text-center p-5">
            <div style="width:80px;height:80px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <i class="bi bi-x-lg" style="font-size:2.2rem;color:#991b1b;"></i>
            </div>

            <h2 style="font-family:'Sora',sans-serif;font-weight:700;font-size:1.6rem;margin-bottom:0.5rem;">Payment Failed</h2>
            <p class="text-muted mb-3">Your payment could not be processed. Your order is still saved.</p>

            @if(session('error'))
                <div style="background:#fff5f5;border:1px solid #fecaca;border-radius:10px;padding:0.85rem;margin-bottom:1.5rem;font-size:0.88rem;color:#991b1b;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="{{ route('orders.index') }}" class="btn" style="background:var(--accent);color:#fff;border:none;border-radius:9px;font-weight:600;padding:0.6rem 1.4rem;font-family:'Sora',sans-serif;">
                    <i class="bi bi-arrow-repeat me-2"></i>Try Again
                </a>
                <a href="{{ route('home') }}" class="btn" style="border:1.5px solid var(--border);border-radius:9px;font-weight:600;padding:0.6rem 1.4rem;color:var(--text-main);font-family:'Sora',sans-serif;">
                    Go Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
