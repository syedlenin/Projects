@extends('layouts.admin')
@section('title', 'Orders')

@section('content')

<div class="card-clean">
    <div class="d-flex align-items-center justify-content-between p-4" style="border-bottom:1px solid var(--border);">
        <p style="font-size:0.82rem;color:var(--text-muted);margin:0;">{{ $orders->total() }} orders total</p>

        {{-- Filter --}}
        <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex gap-2 align-items-center">
            <select name="status" class="form-select" style="font-size:0.82rem;width:auto;min-width:130px;">
                <option value="">All Statuses</option>
                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="payment_status" class="form-select" style="font-size:0.82rem;width:auto;min-width:120px;">
                <option value="">All Payments</option>
                @foreach(['unpaid','paid','failed'] as $p)
                    <option value="{{ $p }}" {{ request('payment_status') == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-brand" style="padding:0.42rem 0.9rem;font-size:0.82rem;">Filter</button>
            @if(request()->hasAny(['status','payment_status']))
                <a href="{{ route('admin.orders.index') }}" style="font-size:0.8rem;color:var(--text-muted);">Clear</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-admin mb-0">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Method</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td style="font-family:'Sora',sans-serif;font-weight:600;font-size:0.85rem;">{{ $order->order_number }}</td>
                        <td>
                            <div style="font-size:0.88rem;font-weight:500;">{{ $order->user->name }}</div>
                            <div style="font-size:0.73rem;color:var(--text-muted);">{{ $order->shipping_phone }}</div>
                        </td>
                        <td style="font-family:'Sora',sans-serif;font-weight:700;">৳ {{ number_format($order->total_amount, 0) }}</td>
                        <td><span class="badge-status badge-{{ $order->status }}">{{ $order->status }}</span></td>
                        <td><span class="badge-status badge-{{ $order->payment_status }}">{{ $order->payment_status }}</span></td>
                        <td style="font-size:0.78rem;text-transform:uppercase;color:var(--text-muted);">{{ $order->payment_method }}</td>
                        <td style="font-size:0.78rem;color:var(--text-muted);">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" style="font-size:0.8rem;font-weight:600;color:var(--accent);text-decoration:none;">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $orders->withQueryString()->links() }}
</div>
@endsection
