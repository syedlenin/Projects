@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number)

@section('content')

<div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
    <a href="{{ route('admin.orders.index') }}" style="font-size:0.82rem;color:var(--text-muted);text-decoration:none;">
        <i class="bi bi-arrow-left me-1"></i>Orders
    </a>
    <h4 style="margin:0;font-weight:700;">{{ $order->order_number }}</h4>
    <span class="badge-status badge-{{ $order->status }}">{{ $order->status }}</span>
    <span class="badge-status badge-{{ $order->payment_status }}">{{ $order->payment_status }}</span>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        {{-- Items --}}
        <div class="card-clean p-4 mb-3">
            <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text-muted);margin-bottom:1rem;border-bottom:1px solid var(--border);padding-bottom:0.5rem;">
                <i class="bi bi-box-seam me-2"></i>Items
            </div>
            @foreach($order->items as $item)
                <div style="display:flex;align-items:center;gap:12px;padding:0.75rem 0;border-bottom:1px solid var(--border);">
                    <img src="{{ $item->product ? $item->product->image_url : asset('images/no-image.png') }}"
                        style="width:50px;height:50px;object-fit:cover;border-radius:8px;background:#f0eeea;flex-shrink:0;">
                    <div style="flex:1;">
                        <div style="font-size:0.88rem;font-weight:600;font-family:'Sora',sans-serif;">{{ $item->product_name }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);">৳ {{ number_format($item->product_price,0) }} × {{ $item->quantity }}</div>
                    </div>
                    <div style="font-weight:700;font-family:'Sora',sans-serif;">৳ {{ number_format($item->subtotal,0) }}</div>
                </div>
            @endforeach

            <div style="padding-top:0.85rem;text-align:right;">
                <div style="font-size:0.82rem;color:var(--text-muted);">Subtotal: <strong>৳ {{ number_format($order->subtotal,0) }}</strong></div>
                <div style="font-size:0.82rem;color:var(--text-muted);">Shipping: <strong>৳ {{ number_format($order->shipping_charge,0) }}</strong></div>
                <div style="font-size:1.1rem;font-weight:700;font-family:'Sora',sans-serif;margin-top:4px;">Total: ৳ {{ number_format($order->total_amount,0) }}</div>
            </div>
        </div>

        {{-- Shipping --}}
        <div class="card-clean p-4">
            <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text-muted);margin-bottom:1rem;border-bottom:1px solid var(--border);padding-bottom:0.5rem;">
                <i class="bi bi-geo-alt me-2"></i>Shipping Address
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                <div>
                    <div style="font-size:0.72rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Name</div>
                    <div style="font-size:0.9rem;font-weight:500;">{{ $order->shipping_name }}</div>
                </div>
                <div>
                    <div style="font-size:0.72rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Phone</div>
                    <div style="font-size:0.9rem;font-weight:500;">{{ $order->shipping_phone }}</div>
                </div>
                <div style="grid-column:span 2;">
                    <div style="font-size:0.72rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Address</div>
                    <div style="font-size:0.9rem;font-weight:500;">{{ $order->shipping_address }}, {{ $order->shipping_city }}</div>
                </div>
                @if($order->notes)
                <div style="grid-column:span 2;">
                    <div style="font-size:0.72rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Notes</div>
                    <div style="font-size:0.88rem;">{{ $order->notes }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Update status --}}
        <div class="card-clean p-4 mb-3">
            <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text-muted);margin-bottom:1rem;border-bottom:1px solid var(--border);padding-bottom:0.5rem;">
                Update Order Status
            </div>
            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                @csrf @method('PATCH')
                <div class="mb-3">
                    <label class="form-label">Order Status</label>
                    <select name="status" class="form-select">
                        @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-brand w-100" style="border-radius:9px;padding:0.6rem;">
                    <i class="bi bi-check-lg me-1"></i>Update Status
                </button>
            </form>
        </div>

        {{-- Payment info --}}
        <div class="card-clean p-4">
            <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text-muted);margin-bottom:1rem;border-bottom:1px solid var(--border);padding-bottom:0.5rem;">
                <i class="bi bi-credit-card me-2"></i>Payment Info
            </div>

            <div class="mb-2">
                <div style="font-size:0.72rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Method</div>
                <div style="font-size:0.9rem;font-weight:600;text-transform:capitalize;">{{ $order->payment_method }}</div>
            </div>
            <div class="mb-2">
                <div style="font-size:0.72rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Status</div>
                <span class="badge-status badge-{{ $order->payment_status }}">{{ $order->payment_status }}</span>
            </div>

            @if($order->payment)
                <div class="mb-2">
                    <div style="font-size:0.72rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Transaction ID</div>
                    <div style="font-size:0.82rem;font-family:monospace;word-break:break-all;">{{ $order->payment->transaction_id ?? '—' }}</div>
                </div>
                @if($order->payment->paid_at)
                    <div>
                        <div style="font-size:0.72rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;">Paid At</div>
                        <div style="font-size:0.82rem;">{{ $order->payment->paid_at->format('d M Y, h:i A') }}</div>
                    </div>
                @endif
            @endif

            {{-- Customer info --}}
            <hr style="border-color:var(--border);">
            <div style="font-size:0.72rem;text-transform:uppercase;color:var(--text-muted);font-weight:600;margin-bottom:0.5rem;">Customer</div>
            <div style="font-size:0.88rem;font-weight:600;">{{ $order->user->name }}</div>
            <div style="font-size:0.8rem;color:var(--text-muted);">{{ $order->user->email }}</div>
        </div>
    </div>
</div>
@endsection
