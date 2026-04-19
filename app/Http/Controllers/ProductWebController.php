<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductWebController extends Controller
{
    /**
     * Display product listing page
     * 
     * GET /products
     * Validates: Requirements 3.1, 3.2, 3.3, 12.2
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->inStock();

        // Filter by category
        if ($request->has('category_id')) {
            $query->byCategory($request->category_id);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort by creation date descending
        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        // Get all categories for filter
        $categories = Category::withCount('products')->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $request->category_id,
            'searchQuery' => $request->search,
        ]);
    }

    /**
     * Display product detail page
     * 
     * GET /products/{id}
     * Validates: Requirements 3.4, 12.2
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return view('products.show', [
            'product' => $product,
        ]);
    }
}
