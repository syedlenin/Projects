<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

// app/Http/Controllers/Admin/ProductController.php
class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }
    public function create(): View
    {
        return view('admin.products.create');
    }
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'category'    => ['nullable', 'string', 'max:100'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'is_active'   => ['boolean'],
        ]);
        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                                          ->store('products', 'public');
        }
        Product::create($validated);
        return redirect()->route('admin.products.index')
                         ->with('success', 'Product created successfully!');
    }
    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'category'    => ['nullable', 'string', 'max:100'],
            'image'       => ['nullable', 'image', 'max:2048'],
            'is_active'   => ['boolean'],
        ]);
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')
                                          ->store('products', 'public');
        }
        $product->update($validated);
        return redirect()->route('admin.products.index')
                         ->with('success', 'Product updated successfully!');
    }
    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')
                         ->with('success', 'Product deleted.');
    }
}
