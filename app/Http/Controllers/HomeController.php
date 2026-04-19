<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage with product catalog
     * 
     * GET /
     * Validates: Requirements 3.1, 12.1, 12.2
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->inStock();

        // Filter by category if provided
        if ($request->has('category_id')) {
            $query->byCategory($request->category_id);
        }

        // Search by name if provided
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort by creation date descending
        $products = $query->orderBy('created_at', 'desc')->get();

        // Get all categories for filter
        $categories = Category::withCount('products')->get();

        return view('home', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $request->category_id,
            'searchQuery' => $request->search,
        ]);
    }
}
