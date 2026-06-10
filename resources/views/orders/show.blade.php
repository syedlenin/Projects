@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)

@push('styles')
<style>
    .detail-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.5rem;
        margin-bottom: 1.2rem;
    }

    .section-label {
        font-family: 'Sora', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border);
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.8rem;
    }

    .info-item label {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 0.2rem;
    }

    .info-item span {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-main);
    }

    .order-item-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0.85rem 0;
        border-bottom: 1px solid var(--border);
    }

    .order-item-row:last-child { border-bottom: none; }

    .order-item-img {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 9px;
        background: #f0eeea;
        flex-shrink: 0;
    }

    .badge-s { font-size:0.72rem;font-weight:600;padding:0.3rem 0.75rem;border-radius:20px;text-transform:capitalize; }
    .st-pending    { background:#fef3c7;color:#92400e; }
    .st-processing { background:#dbeafe;color:#1e40af; }
    .st-shipped    { background:#ede9fe;color:#5b21b6; }
    .st-delivered  { background:#d1fae5;color:#065f46; }
    .st-cancelled  { background:#f3f4f6;color:#6b7280; }
    .pay-paid   { background:#d1fae5;color:#065f46; }
    .pay-unpaid { background:#fee2e2;color:#991b1b; }
    .pay-failed { background:#fee2e2;color:#991b1b; }
</style>
@endpush

@section('content')
<div class="container-xl" style="max-width:860px;">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
        <a href="{{ route('orders.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;">
            <i class="bi bi-arrow-left me-1"></i>Orders
        </a>
        <h1 style="font-size:1.3rem;font-weight:700;margin:0;">{{ $order->order_number }}</h1>
        <span class="badge-s st-{{ $order->status }}">{{ $order->status }}</span>
        <span class="badge-s pay-{{ $order->payment_status }}">{{ $order->payment_status }}</span>
        <span style="font-size:0.78rem;color:var(--text-muted);margin-left:auto;">{{ $order->created_at->format('d M Y, h:i A') }}</span>
    </div>

    <div class="row g-3">
        <div class="col-md-8">

            {{-- Items --}}
            <div class="detail-card">
                <div class="section-label"><i class="bi bi-box-seam me-2"></i>Items Ordered</div>
                @foreach($order->items as $item)
                    <div class="order-item-row">
                        <img src="{{ $item->product ? $item->product->image_url : asset('images/no-image.png') }}"
                             class="order-item-img" alt="{{ $item->product_name }}">
                        <div class="flex-grow-1">
                            <div style="font-family:'Sora',sans-serif;font-size:0.9rem;font-weight:600;">{{ $item->product_name }}</div>
                            <div style="font-size:0.78rem;color:var(--text-muted);">
                                ৳ {{ number_format($item->product_price, 0) }} × {{ $item->quantity }}
                            </div>
                        </div>
                        <div style="font-family:'Sora',sans-serif;font-weight:700;font-size:0.95rem;">
                            ৳ {{ number_format($item->subtotal, 0) }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Shipping address --}}
            <div class="detail-card">
                <div class="section-label"><i class="bi bi-geo-alt me-2"></i>Shipping Address</div>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Name</label>
                        <span>{{ $order->shipping_name }}</span>
                    </div>
                    <div class="info-item">
                        <label>Phone</label>
                        <span>{{ $order->shipping_phone }}</span>
                    </div>
                    <div class="info-item" style="grid-column:span 2;">
                        <label>Address</label>
                        <span>{{ $order->shipping_address }}, {{ $order->shipping_city }}</span>
                    </div>
                </div>

                @if($order->notes)
                    <div class="mt-2">
                        <label style="font-size:0.72rem;font-weight:600;text-transform:uppercase;color:var(--text-muted);">Notes</label>
                        <p style="font-size:0.88rem;color:var(--text-main);margin:0.2rem 0 0;">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

        </div>

        <div class="col-md-4">
            {{-- Payment summary --}}
            <div class="detail-card">
                <div class="section-label"><i class="bi bi-credit-card me-2"></i>Payment</div>

                <div class="mb-2">
                    <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;">Method</div>
                    <div style="font-size:0.9rem;font-weight:600;text-transform:capitalize;">{{ $order->payment_method }}</div>
                </div>

                @if($order->payment)
                    <div class="mb-2">
                        <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;">Transaction ID</div>
                        <div style="font-size:0.82rem;font-family:monospace;">{{ $order->payment->transaction_id ?? '—' }}</div>
                    </div>
                    @if($order->payment->paid_at)
                        <div class="mb-2">
                            <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;">Paid At</div>
                            <div style="font-size:0.82rem;">{{ $order->payment->paid_at->format('d M Y, h:i A') }}</div>
                        </div>
                    @endif
                @endif

                <hr style="border-color:var(--border);">

                <div class="d-flex justify-content-between mb-1" style="font-size:0.88rem;">
                    <span class="text-muted">Subtotal</span>
                    <span>৳ {{ number_format($order->subtotal, 0) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1" style="font-size:0.88rem;">
                    <span class="text-muted">Shipping</span>
                    <span>৳ {{ number_format($order->shipping_charge, 0) }}</span>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span style="font-weight:700;font-family:'Sora',sans-serif;">Total</span>
                    <span style="font-weight:700;font-family:'Sora',sans-serif;font-size:1.1rem;color:var(--primary);">৳ {{ number_format($order->total_amount, 0) }}</span>
                </div>
            </div>

            {{-- Retry payment if unpaid and not COD --}}
            @if($order->payment_status === 'unpaid' && $order->payment_method !== 'cod')
                <form method="POST" action="{{ route('payment.initiate') }}">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <button type="submit" style="background:var(--accent);color:#fff;border:none;border-radius:10px;font-family:'Sora',sans-serif;font-size:0.9rem;font-weight:700;width:100%;padding:0.75rem;cursor:pointer;transition:background 0.2s;">
                        <i class="bi bi-credit-card me-2"></i>Pay Now
                    </button>
                </form>
            @endif
        </div>
    </div>

</div>
@endsection
