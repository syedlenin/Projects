@extends('layouts.admin')
@section('title', 'Products')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <p style="font-size:0.82rem;color:var(--text-muted);margin:0;">{{ $products->total() }} products total</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-accent" style="text-decoration:none;border-radius:9px;padding:0.5rem 1.2rem;font-size:0.85rem;">
        <i class="bi bi-plus-lg me-1"></i>Add Product
    </a>
</div>

<div class="card-clean">
    <div class="table-responsive">
        <table class="table table-admin mb-0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                    style="width:44px;height:44px;object-fit:cover;border-radius:8px;background:#f0eeea;">
                                <div>
                                    <div style="font-family:'Sora',sans-serif;font-size:0.88rem;font-weight:600;">{{ Str::limit($product->name, 32) }}</div>
                                    <div style="font-size:0.73rem;color:var(--text-muted);">ID: {{ $product->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:0.82rem;color:var(--text-muted);">{{ $product->category ?? '—' }}</td>
                        <td style="font-family:'Sora',sans-serif;font-weight:700;font-size:0.9rem;">৳ {{ number_format($product->price, 0) }}</td>
                        <td>
                            <span style="font-weight:600;font-size:0.88rem;color:{{ $product->stock > 0 ? 'var(--text-main)' : 'var(--accent)' }};">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge-status" style="background:#d1fae5;color:#065f46;">Active</span>
                            @else
                                <span class="badge-status" style="background:#f3f4f6;color:#6b7280;">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    style="font-size:0.8rem;font-weight:600;color:var(--text-main);text-decoration:none;background:var(--surface);border-radius:7px;padding:0.3rem 0.7rem;border:1px solid var(--border);">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                    onsubmit="return confirm('Delete {{ $product->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        style="font-size:0.8rem;font-weight:600;color:#991b1b;background:#fee2e2;border-radius:7px;padding:0.3rem 0.7rem;border:none;cursor:pointer;">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>

                                <a href="{{ route('products.show', $product) }}" target="_blank"
                                    style="font-size:0.8rem;font-weight:600;color:var(--accent);text-decoration:none;background:#fff5f7;border-radius:7px;padding:0.3rem 0.7rem;border:1px solid #fecaca;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            No products yet. <a href="{{ route('admin.products.create') }}" style="color:var(--accent);">Add one.</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>
@endsection
