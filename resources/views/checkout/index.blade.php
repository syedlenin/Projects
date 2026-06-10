@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
    .checkout-section-title {
        font-family: 'Sora', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border);
    }

    .checkout-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.6rem;
        margin-bottom: 1.4rem;
    }

    .form-group-clean label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-bottom: 0.35rem;
        display: block;
    }

    .form-group-clean input,
    .form-group-clean textarea,
    .form-group-clean select {
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 0.6rem 0.9rem;
        font-size: 0.9rem;
        width: 100%;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: var(--surface);
        outline: none;
    }

    .form-group-clean input:focus,
    .form-group-clean textarea:focus {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(26,26,46,0.08);
    }

    .form-group-clean .is-invalid {
        border-color: var(--accent) !important;
    }

    .payment-method-option {
        border: 1.5px solid var(--border);
        border-radius: 12px;
        padding: 0.9rem 1rem;
        cursor: pointer;
        transition: all 0.18s;
        margin-bottom: 0.6rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .payment-method-option:hover {
        border-color: var(--primary);
        background: #fafaf8;
    }

    .payment-method-option input[type=radio] { display: none; }

    .payment-method-option.selected {
        border-color: var(--primary);
        background: #f4f4ff;
    }

    .payment-method-option.selected .radio-dot::after {
        opacity: 1;
        transform: scale(1);
    }

    .radio-dot {
        width: 18px;
        height: 18px;
        border: 2px solid var(--border);
        border-radius: 50%;
        flex-shrink: 0;
        position: relative;
        transition: border-color 0.18s;
    }

    .payment-method-option.selected .radio-dot {
        border-color: var(--primary);
    }

    .radio-dot::after {
        content: '';
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%,-50%) scale(0);
        width: 8px; height: 8px;
        border-radius: 50%;
        background: var(--primary);
        opacity: 0;
        transition: all 0.18s;
    }

    .payment-method-option.selected .radio-dot::after {
        opacity: 1;
        transform: translate(-50%,-50%) scale(1);
    }

    .payment-method-label {
        font-family: 'Sora', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .payment-method-desc {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .order-summary-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.5rem;
        position: sticky;
        top: 88px;
    }

    .summary-item {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 0.85rem;
    }

    .summary-item-img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 8px;
        background: #f0eeea;
        flex-shrink: 0;
    }

    .btn-place-order {
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-family: 'Sora', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        width: 100%;
        padding: 0.9rem;
        transition: all 0.2s;
        margin-top: 1rem;
    }

    .btn-place-order:hover {
        background: #ff6b6b;
        color: #fff;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="container-xl">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('cart.index') }}" style="color:var(--text-muted);text-decoration:none;font-size:0.85rem;">
            <i class="bi bi-arrow-left me-1"></i>Back to cart
        </a>
        <h1 style="font-size:1.5rem;font-weight:700;margin:0;">Checkout</h1>
    </div>

    <form method="POST" action="{{ route('checkout.place-order') }}" id="checkoutForm">
        @csrf

        <div class="row g-4">
            {{-- Left: shipping + payment --}}
            <div class="col-lg-7">

                {{-- Shipping Address --}}
                <div class="checkout-card">
                    <div class="checkout-section-title"><i class="bi bi-geo-alt me-2"></i>Shipping Address</div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group-clean">
                                <label>Full Name *</label>
                                <input type="text" name="shipping_name"
                                    value="{{ old('shipping_name', auth()->user()->name) }}"
                                    placeholder="Your full name"
                                    class="{{ $errors->has('shipping_name') ? 'is-invalid' : '' }}">
                                @error('shipping_name')
                                    <small style="color:var(--accent);">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-clean">
                                <label>Phone Number *</label>
                                <input type="text" name="shipping_phone"
                                    value="{{ old('shipping_phone', auth()->user()->phone ?? '') }}"
                                    placeholder="01XXXXXXXXX"
                                    class="{{ $errors->has('shipping_phone') ? 'is-invalid' : '' }}">
                                @error('shipping_phone')
                                    <small style="color:var(--accent);">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group-clean">
                                <label>Full Address *</label>
                                <textarea name="shipping_address" rows="2"
                                    placeholder="House #, Road #, Area"
                                    class="{{ $errors->has('shipping_address') ? 'is-invalid' : '' }}">{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                                @error('shipping_address')
                                    <small style="color:var(--accent);">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-clean">
                                <label>City *</label>
                                <input type="text" name="shipping_city"
                                    value="{{ old('shipping_city', 'Dhaka') }}"
                                    placeholder="e.g. Dhaka"
                                    class="{{ $errors->has('shipping_city') ? 'is-invalid' : '' }}">
                                @error('shipping_city')
                                    <small style="color:var(--accent);">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-clean">
                                <label>Order Notes (optional)</label>
                                <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Special instructions…">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="checkout-card">
                    <div class="checkout-section-title"><i class="bi bi-credit-card me-2"></i>Payment Method</div>

                    @error('payment_method')
                        <small class="d-block mb-2" style="color:var(--accent);">{{ $message }}</small>
                    @enderror

                    @php
                        $methods = [
                            'sslcommerz' => ['label' => 'SSLCommerz', 'desc' => 'Cards, bKash, Nagad, Rocket & more', 'icon' => 'bi-credit-card-2-front'],
                            'aamarpay'   => ['label' => 'aamarPay',   'desc' => 'Mobile banking & debit/credit cards', 'icon' => 'bi-phone'],
                            'shurjopay'  => ['label' => 'ShurjoPay',  'desc' => 'Fast and secure Bangladeshi gateway', 'icon' => 'bi-lightning'],
                            'cod'        => ['label' => 'Cash on Delivery', 'desc' => 'Pay when your order arrives', 'icon' => 'bi-cash-coin'],
                        ];
                        $selectedMethod = old('payment_method', 'sslcommerz');
                    @endphp

                    @foreach($methods as $value => $method)
                        <label class="payment-method-option {{ $selectedMethod == $value ? 'selected' : '' }}" onclick="selectPayment('{{ $value }}', this)">
                            <input type="radio" name="payment_method" value="{{ $value }}" {{ $selectedMethod == $value ? 'checked' : '' }}>
                            <div class="radio-dot"></div>
                            <i class="bi {{ $method['icon'] }}" style="font-size:1.2rem;color:var(--primary);width:24px;text-align:center;"></i>
                            <div>
                                <div class="payment-method-label">{{ $method['label'] }}</div>
                                <div class="payment-method-desc">{{ $method['desc'] }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Right: order summary --}}
            <div class="col-lg-5">
                <div class="order-summary-card">
                    <h5 style="font-family:'Sora',sans-serif;font-weight:700;margin-bottom:1.2rem;">Order Summary</h5>

                    @foreach($cartItems as $item)
                        <div class="summary-item">
                            <img src="{{ $item->product->image_url }}" class="summary-item-img" alt="{{ $item->product->name }}">
                            <div class="flex-grow-1">
                                <div style="font-size:0.85rem;font-weight:600;font-family:'Sora',sans-serif;">{{ Str::limit($item->product->name, 30) }}</div>
                                <div style="font-size:0.78rem;color:var(--text-muted);">Qty: {{ $item->quantity }}</div>
                            </div>
                            <div style="font-weight:700;font-size:0.9rem;font-family:'Sora',sans-serif;white-space:nowrap;">
                                ৳ {{ number_format($item->product->price * $item->quantity, 0) }}
                            </div>
                        </div>
                    @endforeach

                    <hr style="border-color:var(--border);">

                    <div class="d-flex justify-content-between mb-2" style="font-size:0.9rem;">
                        <span class="text-muted">Subtotal</span>
                        <span style="font-weight:600;">৳ {{ number_format($subtotal, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size:0.9rem;">
                        <span class="text-muted">Shipping</span>
                        <span style="font-weight:600;">৳ {{ number_format($shippingCharge, 0) }}</span>
                    </div>

                    <hr style="border-color:var(--border);">

                    <div class="d-flex justify-content-between">
                        <span style="font-weight:700;font-family:'Sora',sans-serif;">Total</span>
                        <span style="font-family:'Sora',sans-serif;font-size:1.4rem;font-weight:700;color:var(--primary);">৳ {{ number_format($total, 0) }}</span>
                    </div>

                    <button type="submit" class="btn-place-order">
                        <i class="bi bi-lock-fill me-2"></i>Place Order
                    </button>

                    <p style="font-size:0.72rem;color:var(--text-muted);text-align:center;margin-top:0.75rem;">
                        <i class="bi bi-shield-check me-1"></i>Secured by SSL · Your data is safe
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function selectPayment(value, el) {
    document.querySelectorAll('.payment-method-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input[type=radio]').checked = true;
}
</script>
@endpush
