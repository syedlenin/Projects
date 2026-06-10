<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    /**
     * Show all active products with optional filtering
     */
    public function index(Request $request): View
    {
        $query = Product::where('is_active', true);
        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }
        // Sort options
        match($request->get('sort')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest'     => $query->latest(),
            default      => $query->latest(),
        };
        $products = $query->paginate(12);
        return view('products.index', compact('products'));
    }
    /**
     * Show single product details
     */
    public function show(Product $product): View
    {
        abort_if(!$product->is_active, 404);
        // Related products from same category
        $related = Product::where('category', $product->category)
                          ->where('id', '!=', $product->id)
                          ->where('is_active', true)
                          ->take(4)
                          ->get();
        return view('products.show', compact('product', 'related'));
    }
}
