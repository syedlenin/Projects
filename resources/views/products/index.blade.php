@extends('layouts.app')

@section('title', 'Shop')

@push('styles')
<style>
    .filter-bar {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 1.1rem 1.4rem;
        margin-bottom: 2rem;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.4rem;
    }

    .product-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        transition: transform 0.22s, box-shadow 0.22s;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.1);
    }

    .product-img-wrap {
        aspect-ratio: 4/3;
        overflow: hidden;
        background: #f0eeea;
    }

    .product-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s;
    }

    .product-card:hover .product-img-wrap img {
        transform: scale(1.06);
    }

    .product-body {
        padding: 1rem 1.1rem 1.2rem;
    }

    .product-category {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 0.3rem;
    }

    .product-name {
        font-family: 'Sora', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-price {
        font-family: 'Sora', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
    }

    .product-price::before { content: '৳ '; font-size: 0.8rem; }

    .out-of-stock-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: #fff;
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 3px 8px;
        border-radius: 20px;
    }

    .btn-add-cart {
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        width: 100%;
        padding: 0.52rem;
        margin-top: 0.75rem;
        transition: background 0.2s;
        font-family: 'Sora', sans-serif;
    }

    .btn-add-cart:hover:not(:disabled) { background: var(--accent); color: #fff; }
    .btn-add-cart:disabled { background: #ccc; cursor: not-allowed; }

    .hero-strip {
        background: var(--primary);
        color: #fff;
        border-radius: 16px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .hero-strip::after {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 200px; height: 200px;
        background: var(--accent);
        border-radius: 50%;
        opacity: 0.12;
    }

    .sort-select {
        border-radius: 8px;
        border: 1px solid var(--border);
        font-size: 0.85rem;
        padding: 0.42rem 0.8rem;
    }
</style>
@endpush

@section('content')
<div class="container-xl">

    {{-- Hero --}}
    <div class="hero-strip">
        <h1 style="font-size:1.8rem;font-weight:700;margin-bottom:0.3rem;">Our Products</h1>
        <p style="color:rgba(255,255,255,0.65);margin:0;font-size:0.9rem;">Pay with SSLCommerz, aamarPay or ShurjoPay</p>
    </div>

    {{-- Filter bar --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('products.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
            <input
                type="text"
                name="search"
                class="form-control"
                style="max-width:220px;border-radius:8px;font-size:0.88rem;"
                placeholder="Search products…"
                value="{{ request('search') }}"
            >

            <select name="category" class="form-select sort-select" style="max-width:160px;">
                <option value="">All Categories</option>
                @php
                    $categories = \App\Models\Product::select('category')->distinct()->whereNotNull('category')->pluck('category');
                @endphp
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <select name="sort" class="form-select sort-select" style="max-width:150px;">
                <option value="newest" {{ request('sort','newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
            </select>

            <button type="submit" class="btn btn-primary-brand" style="background:var(--primary);color:#fff;border:none;border-radius:8px;padding:0.45rem 1.1rem;font-size:0.88rem;font-weight:600;">
                <i class="bi bi-search me-1"></i>Filter
            </button>

            @if(request()->hasAny(['search','category','sort']))
                <a href="{{ route('products.index') }}" class="btn" style="font-size:0.85rem;color:var(--text-muted);">
                    <i class="bi bi-x-circle me-1"></i>Clear
                </a>
            @endif

            <span class="ms-auto text-muted" style="font-size:0.82rem;">
                {{ $products->total() }} product{{ $products->total() != 1 ? 's' : '' }}
            </span>
        </form>
    </div>

    {{-- Products grid --}}
    @if($products->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-search" style="font-size:2.5rem;color:#ccc;"></i>
            <p class="mt-3 text-muted">No products found. <a href="{{ route('products.index') }}">Clear filters</a></p>
        </div>
    @else
        <div class="product-grid">
            @foreach($products as $product)
                <div class="product-card">
                    @if(!$product->is_active || $product->stock == 0)
                        <span class="out-of-stock-badge">Out of Stock</span>
                    @endif

                    <a href="{{ route('products.show', $product) }}" class="d-block">
                        <div class="product-img-wrap">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                        </div>
                    </a>

                    <div class="product-body">
                        @if($product->category)
                            <div class="product-category">{{ $product->category }}</div>
                        @endif
                        <a href="{{ route('products.show', $product) }}" style="text-decoration:none;">
                            <div class="product-name">{{ $product->name }}</div>
                        </a>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <span class="product-price">{{ number_format($product->price, 0) }}</span>
                            <span style="font-size:0.75rem;color:var(--text-muted);">
                                {{ $product->stock > 0 ? $product->stock . ' left' : '' }}
                            </span>
                        </div>

                        @auth
                            <form method="POST" action="{{ route('cart.add', $product) }}">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button
                                    type="submit"
                                    class="btn-add-cart"
                                    {{ $product->stock == 0 ? 'disabled' : '' }}
                                >
                                    <i class="bi bi-bag-plus me-1"></i>
                                    {{ $product->stock == 0 ? 'Out of Stock' : 'Add to Cart' }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-add-cart d-block text-center text-decoration-none">
                                <i class="bi bi-bag-plus me-1"></i>Add to Cart
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif

</div>
@endsection
