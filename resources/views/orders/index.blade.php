@extends('layouts.app')
@section('title', 'My Orders')

@push('styles')
<style>
    .order-row {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.2rem 1.5rem;
        margin-bottom: 0.9rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        flex-wrap: wrap;
        transition: box-shadow 0.2s;
        text-decoration: none;
        color: inherit;
    }

    .order-row:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.07);
        color: inherit;
    }

    .order-number {
        font-family: 'Sora', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--primary);
    }

    .order-date {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .order-total {
        font-family: 'Sora', sans-serif;
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--primary);
        margin-left: auto;
    }

    .badge-order-status {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        text-transform: capitalize;
    }

    .status-pending    { background:#fef3c7;color:#92400e; }
    .status-processing { background:#dbeafe;color:#1e40af; }
    .status-shipped    { background:#ede9fe;color:#5b21b6; }
    .status-delivered  { background:#d1fae5;color:#065f46; }
    .status-cancelled  { background:#f3f4f6;color:#6b7280; }
    .pay-paid   { background:#d1fae5;color:#065f46; }
    .pay-unpaid { background:#fee2e2;color:#991b1b; }
    .pay-failed { background:#fee2e2;color:#991b1b; }
</style>
@endpush

@section('content')
<div class="container-xl" style="max-width:860px;">
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;">My Orders</h1>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:3.5rem;color:#ddd;"><i class="bi bi-receipt"></i></div>
            <h5 style="font-family:'Sora',sans-serif;font-weight:600;margin-top:1rem;">No orders yet</h5>
            <p class="text-muted mb-3">Start shopping to see your orders here.</p>
            <a href="{{ route('products.index') }}" style="background:var(--accent);color:#fff;border-radius:9px;padding:0.6rem 1.5rem;font-weight:600;font-family:'Sora',sans-serif;text-decoration:none;font-size:0.9rem;">
                <i class="bi bi-grid me-2"></i>Browse Products
            </a>
        </div>
    @else
        @foreach($orders as $order)
            <a href="{{ route('orders.show', $order) }}" class="order-row d-flex">
                {{-- Order info --}}
                <div>
                    <div class="order-number">{{ $order->order_number }}</div>
                    <div class="order-date">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                </div>

                {{-- Status badges --}}
                <div class="d-flex gap-2 flex-wrap ms-3">
                    <span class="badge-order-status status-{{ $order->status }}">{{ $order->status }}</span>
                    <span class="badge-order-status pay-{{ $order->payment_status }}">{{ $order->payment_status }}</span>
                </div>

                {{-- Payment method --}}
                <span style="font-size:0.78rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">
                    {{ $order->payment_method }}
                </span>

                {{-- Total --}}
                <div class="order-total">৳ {{ number_format($order->total_amount, 0) }}</div>

                <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:0.85rem;"></i>
            </a>
        @endforeach

        <div class="d-flex justify-content-center mt-3">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
