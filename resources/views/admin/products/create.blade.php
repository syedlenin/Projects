@extends('layouts.admin')
@section('title', 'Add Product')

@section('content')

<div style="max-width:700px;">
    <a href="{{ route('admin.products.index') }}" style="font-size:0.82rem;color:var(--text-muted);text-decoration:none;display:inline-block;margin-bottom:1.5rem;">
        <i class="bi bi-arrow-left me-1"></i>Back to Products
    </a>

    <div class="card-clean p-4">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        value="{{ old('name') }}" placeholder="e.g. Premium Cotton T-Shirt">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Price (BDT) *</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius:8px 0 0 8px;border-color:var(--border);">৳</span>
                        <input type="number" name="price" step="0.01" min="0"
                            class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                            style="border-radius:0 8px 8px 0;"
                            value="{{ old('price') }}" placeholder="0.00">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Stock Quantity *</label>
                    <input type="number" name="stock" min="0"
                        class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}"
                        value="{{ old('stock', 0) }}" placeholder="0">
                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <input type="text" name="category"
                        class="form-control"
                        value="{{ old('category') }}" placeholder="e.g. Clothing">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control" placeholder="Product description…">{{ old('description') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Product Image</label>
                    <input type="file" name="image" accept="image/*"
                        class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}"
                        onchange="previewImage(this)">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div id="imgPreview" style="margin-top:0.75rem;display:none;">
                        <img id="previewImg" src="" style="width:100px;height:100px;object-fit:cover;border-radius:10px;border:1px solid var(--border);">
                    </div>
                    <small style="color:var(--text-muted);font-size:0.75rem;">Max 2MB. JPG, PNG, WEBP.</small>
                </div>
            </div>

            <hr style="border-color:var(--border);margin:1.5rem 0;">

            <div class="d-flex gap-2">
                <button type="submit" class="btn-accent" style="border-radius:9px;padding:0.6rem 1.5rem;font-size:0.9rem;">
                    <i class="bi bi-plus-lg me-1"></i>Create Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn" style="border:1.5px solid var(--border);border-radius:9px;padding:0.6rem 1.2rem;font-size:0.9rem;color:var(--text-main);">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imgPreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
