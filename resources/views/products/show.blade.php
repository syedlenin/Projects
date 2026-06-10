@extends('layouts.app')

@section('title', $product->name)

@push('styles')
<style>
    .product-detail-img {
        border-radius: 16px;
        overflow: hidden;
        aspect-ratio: 1;
        background: #f0eeea;
    }

    .product-detail-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-info-block {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 2rem;
    }

    .product-detail-price {
        font-family: 'Sora', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
    }

    .qty-control {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        width: fit-content;
    }

    .qty-btn {
        background: var(--surface);
        border: none;
        width: 40px;
        height: 42px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background 0.15s;
        font-weight: 600;
    }

    .qty-btn:hover { background: #e8e6e1; }

    .qty-input {
        border: none;
        border-left: 1px solid var(--border);
        border-right: 1px solid var(--border);
        width: 55px;
        height: 42px;
        text-align: center;
        font-size: 0.95rem;
        font-weight: 600;
        outline: none;
    }

    .btn-add-to-cart-lg {
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-family: 'Sora', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        padding: 0.75rem 2rem;
        transition: all 0.2s;
        flex: 1;
    }

    .btn-add-to-cart-lg:hover:not(:disabled) {
        background: var(--accent);
        color: #fff;
        transform: translateY(-1px);
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1.1rem;
    }

    .related-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s, box-shadow 0.2s;
        display: block;
    }

    .related-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        color: inherit;
    }

    .related-img {
        aspect-ratio: 1;
        overflow: hidden;
        background: #f0eeea;
    }

    .related-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .related-card:hover .related-img img { transform: scale(1.06); }
</style>
@endpush

@section('content')
<div class="container-xl">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:0.82rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color:var(--accent);">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none" style="color:var(--accent);">Products</a></li>
            <li class="breadcrumb-item active text-muted">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Image --}}
        <div class="col-md-5">
            <div class="product-detail-img">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            </div>
        </div>

        {{-- Info --}}
        <div class="col-md-7">
            <div class="product-info-block">
                @if($product->category)
                    <span style="font-size:0.72rem;font-weight:600;letter-spacing:0.8px;text-transform:uppercase;color:var(--accent);">
                        {{ $product->category }}
                    </span>
                @endif

                <h1 style="font-size:1.6rem;font-weight:700;margin:0.5rem 0 0.8rem;">{{ $product->name }}</h1>

                <div class="product-detail-price mb-1">৳ {{ number_format($product->price, 2) }}</div>

                <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:1.5rem;">
                    @if($product->stock > 0)
                        <i class="bi bi-check-circle-fill text-success me-1"></i>In stock
                        <span class="ms-2 text-muted">({{ $product->stock }} available)</span>
                    @else
                        <i class="bi bi-x-circle-fill text-danger me-1"></i>Out of stock
                    @endif
                </p>

                @if($product->description)
                    <p style="font-size:0.9rem;color:var(--text-muted);line-height:1.7;margin-bottom:1.5rem;">
                        {{ $product->description }}
                    </p>
                @endif

                <hr style="border-color:var(--border);">

                @auth
                    @if($product->stock > 0)
                        <form method="POST" action="{{ route('cart.add', $product) }}">
                            @csrf
                            <div class="mb-3">
                                <label style="font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);display:block;margin-bottom:0.5rem;">Quantity</label>
                                <div class="qty-control">
                                    <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                                    <input type="number" name="quantity" id="qty" class="qty-input" value="1" min="1" max="{{ $product->stock }}">
                                    <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn-add-to-cart-lg">
                                    <i class="bi bi-bag-plus me-2"></i>Add to Cart
                                </button>
                                <a href="{{ route('cart.index') }}" class="btn" style="border:1.5px solid var(--border);border-radius:10px;padding:0.75rem 1.2rem;font-weight:600;font-size:0.9rem;color:var(--text-main);">
                                    <i class="bi bi-bag"></i>
                                </a>
                            </div>
                        </form>
                    @else
                        <button class="btn-add-to-cart-lg w-100" disabled style="background:#ccc;cursor:not-allowed;">
                            <i class="bi bi-x-circle me-2"></i>Out of Stock
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-add-to-cart-lg d-block text-center text-decoration-none py-3">
                        <i class="bi bi-person-circle me-2"></i>Login to Add to Cart
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($related->isNotEmpty())
        <div class="mt-5">
            <h3 style="font-size:1.15rem;font-weight:700;margin-bottom:1.2rem;">Related Products</h3>
            <div class="related-grid">
                @foreach($related as $rel)
                    <a href="{{ route('products.show', $rel) }}" class="related-card">
                        <div class="related-img">
                            <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}">
                        </div>
                        <div style="padding:0.75rem 0.9rem;">
                            <div style="font-size:0.85rem;font-weight:600;font-family:'Sora',sans-serif;margin-bottom:0.2rem;">{{ Str::limit($rel->name, 28) }}</div>
                            <div style="font-size:0.9rem;font-weight:700;color:var(--primary);">৳ {{ number_format($rel->price, 0) }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('qty');
    const max = parseInt(input.max);
    const min = parseInt(input.min);
    let val = parseInt(input.value) + delta;
    val = Math.max(min, Math.min(max, val));
    input.value = val;
}
</script>
@endpush
