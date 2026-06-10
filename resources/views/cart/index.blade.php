@extends('layouts.app')

@section('title', 'My Cart')

@push('styles')
<style>
    .cart-table th {
        font-family: 'Sora', sans-serif;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border);
        padding: 0.85rem 1rem;
        background: var(--surface);
    }

    .cart-table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border);
    }

    .cart-product-img {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 10px;
        background: #f0eeea;
    }

    .cart-product-name {
        font-family: 'Sora', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-main);
        text-decoration: none;
    }

    .cart-product-name:hover { color: var(--accent); }

    .qty-inline {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        width: fit-content;
    }

    .qty-inline-btn {
        background: var(--surface);
        border: none;
        width: 32px;
        height: 34px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s;
    }

    .qty-inline-btn:hover { background: #ddd; }

    .qty-inline-input {
        border: none;
        border-left: 1px solid var(--border);
        border-right: 1px solid var(--border);
        width: 46px;
        height: 34px;
        text-align: center;
        font-size: 0.88rem;
        font-weight: 600;
        outline: none;
    }

    .summary-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.5rem;
        position: sticky;
        top: 90px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        margin-bottom: 0.6rem;
    }

    .summary-total {
        font-family: 'Sora', sans-serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary);
    }

    .btn-checkout {
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-family: 'Sora', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        width: 100%;
        padding: 0.85rem;
        transition: all 0.2s;
        margin-top: 0.5rem;
    }

    .btn-checkout:hover {
        background: #ff6b6b;
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-remove {
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 1rem;
        cursor: pointer;
        padding: 0.3rem 0.5rem;
        border-radius: 6px;
        transition: all 0.15s;
    }

    .btn-remove:hover { color: var(--accent); background: #fee2e2; }

    .payment-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .payment-badge {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-muted);
    }
</style>
@endpush

@section('content')
<div class="container-xl">
    <div class="d-flex align-items-center gap-3 mb-4">
        <h1 style="font-size:1.5rem;font-weight:700;margin:0;">My Cart</h1>
        @if($cartItems->isNotEmpty())
            <span style="background:var(--accent);color:#fff;font-size:0.72rem;font-weight:700;padding:3px 9px;border-radius:20px;">
                {{ $cartItems->count() }} item{{ $cartItems->count() != 1 ? 's' : '' }}
            </span>
        @endif
    </div>

    @if($cartItems->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:4rem;color:#ddd;"><i class="bi bi-bag"></i></div>
            <h4 style="font-family:'Sora',sans-serif;font-weight:600;margin-top:1rem;">Your cart is empty</h4>
            <p class="text-muted mb-3">Add some products to get started</p>
            <a href="{{ route('products.index') }}" class="btn btn-accent" style="background:var(--accent);color:#fff;border:none;border-radius:10px;padding:0.65rem 1.8rem;font-weight:600;font-family:'Sora',sans-serif;">
                <i class="bi bi-grid me-2"></i>Browse Products
            </a>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-clean">
                    <table class="table cart-table mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr>
                                    {{-- Image + name --}}
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $item->product->image_url }}" class="cart-product-img" alt="{{ $item->product->name }}">
                                            <div>
                                                <a href="{{ route('products.show', $item->product) }}" class="cart-product-name d-block">
                                                    {{ $item->product->name }}
                                                </a>
                                                @if($item->product->category)
                                                    <span style="font-size:0.72rem;color:var(--text-muted);">{{ $item->product->category }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Unit price --}}
                                    <td style="font-weight:600;font-family:'Sora',sans-serif;">
                                        ৳ {{ number_format($item->product->price, 0) }}
                                    </td>

                                    {{-- Quantity update --}}
                                    <td>
                                        <form method="POST" action="{{ route('cart.update', $item) }}" class="d-flex align-items-center gap-2">
                                            @csrf @method('PATCH')
                                            <div class="qty-inline">
                                                <button type="button" class="qty-inline-btn" onclick="this.closest('form').querySelector('input').value = Math.max(1, parseInt(this.closest('form').querySelector('input').value)-1)">−</button>
                                                <input type="number" name="quantity" class="qty-inline-input"
                                                    value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}">
                                                <button type="button" class="qty-inline-btn" onclick="this.closest('form').querySelector('input').value = Math.min({{ $item->product->stock }}, parseInt(this.closest('form').querySelector('input').value)+1)">+</button>
                                            </div>
                                            <button type="submit" style="background:none;border:none;font-size:0.75rem;color:var(--accent);font-weight:600;cursor:pointer;padding:0;">Update</button>
                                        </form>
                                    </td>

                                    {{-- Subtotal --}}
                                    <td style="font-weight:700;font-family:'Sora',sans-serif;color:var(--primary);">
                                        ৳ {{ number_format($item->product->price * $item->quantity, 0) }}
                                    </td>

                                    {{-- Remove --}}
                                    <td>
                                        <form method="POST" action="{{ route('cart.remove', $item) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-remove" title="Remove">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <a href="{{ route('products.index') }}" style="font-size:0.85rem;color:var(--text-muted);text-decoration:none;">
                        <i class="bi bi-arrow-left me-1"></i>Continue shopping
                    </a>
                </div>
            </div>

            {{-- Summary --}}
            <div class="col-lg-4">
                <div class="summary-card">
                    <h5 style="font-family:'Sora',sans-serif;font-weight:700;margin-bottom:1.2rem;">Order Summary</h5>

                    <div class="summary-row">
                        <span class="text-muted">Subtotal</span>
                        <span style="font-weight:600;">৳ {{ number_format($total, 0) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="text-muted">Shipping</span>
                        <span style="font-weight:600;">৳ 60</span>
                    </div>

                    <hr style="border-color:var(--border);">

                    <div class="summary-row">
                        <span style="font-weight:700;">Total</span>
                        <span class="summary-total">৳ {{ number_format($total + 60, 0) }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn-checkout d-block text-center text-decoration-none">
                        <i class="bi bi-shield-lock me-2"></i>Proceed to Checkout
                    </a>

                    <div class="payment-badges">
                        <span class="payment-badge"><i class="bi bi-credit-card me-1"></i>SSLCommerz</span>
                        <span class="payment-badge">aamarPay</span>
                        <span class="payment-badge">ShurjoPay</span>
                        <span class="payment-badge">COD</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
