@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    @php
        $cards = [
            ['label'=>'Total Revenue',   'value'=>'৳ ' . number_format($stats['total_revenue'], 0), 'icon'=>'bi-currency-dollar', 'color'=>'#d1fae5', 'icolor'=>'#065f46'],
            ['label'=>'Total Orders',    'value'=>$stats['total_orders'],    'icon'=>'bi-receipt',        'color'=>'#dbeafe', 'icolor'=>'#1e40af'],
            ['label'=>'Pending Orders',  'value'=>$stats['pending_orders'],  'icon'=>'bi-hourglass-split','color'=>'#fef3c7', 'icolor'=>'#92400e'],
            ['label'=>'Products',        'value'=>$stats['total_products'],  'icon'=>'bi-box-seam',       'color'=>'#ede9fe', 'icolor'=>'#5b21b6'],
            ['label'=>'Customers',       'value'=>$stats['total_users'],     'icon'=>'bi-people',         'color'=>'#fce7f3', 'icolor'=>'#9d174d'],
        ];
    @endphp

    @foreach($cards as $card)
        <div class="col-6 col-md-4 col-xl-2-4" style="flex: 0 0 calc(20% - 0.6rem);">
            <div class="stat-card">
                <div class="stat-icon mb-3" style="background:{{ $card['color'] }};">
                    <i class="bi {{ $card['icon'] }}" style="color:{{ $card['icolor'] }};"></i>
                </div>
                <div class="stat-value">{{ $card['value'] }}</div>
                <div style="font-size:0.78rem;color:var(--text-muted);margin-top:4px;">{{ $card['label'] }}</div>
            </div>
        </div>
    @endforeach
</div>

{{-- Recent orders --}}
<div class="card-clean">
    <div class="d-flex align-items-center justify-content-between p-4" style="border-bottom:1px solid var(--border);">
        <h5 style="font-family:'Sora',sans-serif;font-weight:700;margin:0;">Recent Orders</h5>
        <a href="{{ route('admin.orders.index') }}" style="font-size:0.82rem;color:var(--accent);text-decoration:none;font-weight:600;">
            View all <i class="bi bi-arrow-right ms-1"></i>
        </a>
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
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td>
                            <span style="font-family:'Sora',sans-serif;font-weight:600;font-size:0.85rem;">{{ $order->order_number }}</span>
                        </td>
                        <td>
                            <div style="font-size:0.88rem;font-weight:500;">{{ $order->user->name }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ $order->user->email }}</div>
                        </td>
                        <td style="font-family:'Sora',sans-serif;font-weight:700;">৳ {{ number_format($order->total_amount, 0) }}</td>
                        <td>
                            <span class="badge-status badge-{{ $order->status }}">{{ $order->status }}</span>
                        </td>
                        <td>
                            <span class="badge-status badge-{{ $order->payment_status }}">{{ $order->payment_status }}</span>
                        </td>
                        <td style="font-size:0.8rem;color:var(--text-muted);">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" style="font-size:0.8rem;color:var(--accent);text-decoration:none;font-weight:600;">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No orders yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
