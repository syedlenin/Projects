@extends('layouts.admin')
@section('title', 'Payment Logs')

@section('content')

<div class="card-clean">
    <div class="p-4" style="border-bottom:1px solid var(--border);">
        <p style="font-size:0.82rem;color:var(--text-muted);margin:0;">{{ $payments->total() }} payment records</p>
    </div>

    <div class="table-responsive">
        <table class="table table-admin mb-0">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Gateway</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Transaction ID</th>
                    <th>Paid At</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $payment->order) }}"
                                style="font-family:'Sora',sans-serif;font-size:0.85rem;font-weight:600;color:var(--accent);text-decoration:none;">
                                {{ $payment->order->order_number ?? '—' }}
                            </a>
                        </td>
                        <td>
                            <div style="font-size:0.85rem;font-weight:500;">{{ $payment->user->name ?? '—' }}</div>
                            <div style="font-size:0.72rem;color:var(--text-muted);">{{ $payment->user->email ?? '' }}</div>
                        </td>
                        <td>
                            <span style="font-size:0.78rem;font-weight:600;text-transform:uppercase;
                                background:var(--surface);border-radius:6px;padding:3px 8px;border:1px solid var(--border);">
                                {{ $payment->payment_method }}
                            </span>
                        </td>
                        <td style="font-family:'Sora',sans-serif;font-weight:700;">
                            ৳ {{ number_format($payment->amount, 0) }}
                        </td>
                        <td>
                            <span class="badge-status badge-{{ $payment->status }}">{{ $payment->status }}</span>
                        </td>
                        <td>
                            @if($payment->transaction_id)
                                <code style="font-size:0.75rem;background:var(--surface);padding:2px 6px;border-radius:4px;">
                                    {{ Str::limit($payment->transaction_id, 20) }}
                                </code>
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                        <td style="font-size:0.78rem;color:var(--text-muted);">
                            {{ $payment->paid_at ? $payment->paid_at->format('d M Y, h:i A') : '—' }}
                        </td>
                        <td>
                            {{-- Gateway response modal trigger --}}
                            @if($payment->gateway_response)
                                <button type="button"
                                    class="btn" style="font-size:0.78rem;padding:0.25rem 0.6rem;border:1px solid var(--border);border-radius:6px;color:var(--text-muted);"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal{{ $payment->id }}">
                                    <i class="bi bi-code-slash"></i>
                                </button>

                                <div class="modal fade" id="modal{{ $payment->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content" style="border-radius:14px;border:none;">
                                            <div class="modal-header" style="border-bottom:1px solid var(--border);">
                                                <h6 class="modal-title" style="font-family:'Sora',sans-serif;font-weight:700;">
                                                    Gateway Response — {{ $payment->order->order_number ?? '' }}
                                                </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <pre style="font-size:0.75rem;background:var(--surface);border-radius:10px;padding:1rem;overflow:auto;max-height:400px;">{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">No payment records yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $payments->links() }}
</div>
@endsection
