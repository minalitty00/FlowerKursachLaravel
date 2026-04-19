<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filtering and search.
     * 
     * Validates: Requirements 2.6, 3.1, 3.2, 3.3, 3.5
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->has('category_id')) {
            $query->byCategory($request->category_id);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter in stock products (default behavior for users)
        if ($request->boolean('in_stock', true)) {
            $query->inStock();
        }

        // Sort by creation date descending
        $query->orderBy('created_at', 'desc');

        $products = $query->get();

        return response()->json([
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'image_url' => $product->image_path ? asset('storage/' . $product->image_path) : null,
                    'category' => [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug,
                    ],
                    'stock_quantity' => $product->stock_quantity,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ];
            }),
        ]);
    }

    /**
     * Display the specified product.
     * 
     * Validates: Requirements 3.4
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'image_url' => $product->image_path ? asset('storage/' . $product->image_path) : null,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ],
                'stock_quantity' => $product->stock_quantity,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ],
        ]);
    }

    /**
     * Store a newly created product.
     * 
     * Validates: Requirements 2.1, 2.7
     *
     * @param StoreProductRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'stock_quantity' => $validated['stock_quantity'],
            'image_path' => $imagePath,
        ]);

        $product->load('category');

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Product created successfully',
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'image_url' => $product->image_path ? asset('storage/' . $product->image_path) : null,
                    'category' => [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug,
                    ],
                    'stock_quantity' => $product->stock_quantity,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ],
            ], 201);
        }

        // Return redirect for web requests
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно создан!');
    }

    /**
     * Update the specified product.
     * 
     * Validates: Requirements 2.2
     *
     * @param UpdateProductRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        $product->load('category');

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Product updated successfully',
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'image_url' => $product->image_path ? asset('storage/' . $product->image_path) : null,
                    'category' => [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug,
                    ],
                    'stock_quantity' => $product->stock_quantity,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ],
            ]);
        }

        // Return redirect for web requests
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно обновлён!');
    }

    /**
     * Remove the specified product.
     * 
     * Validates: Requirements 2.3, 13.5
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Delete associated image if exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Product deleted successfully',
            ]);
        }

        // Return redirect for web requests
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно удалён!');
    }
}
