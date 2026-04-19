<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories with product count.
     * 
     * Validates: Requirements 9.5
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::withCount('products')->get();

        return response()->json([
            'categories' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'product_count' => $category->products_count,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                ];
            }),
        ]);
    }

    /**
     * Store a newly created category.
     * 
     * Validates: Requirements 9.1, 9.4
     *
     * @param StoreCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Category created successfully',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                ],
            ], 201);
        }

        // Return redirect for web requests
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно создана!');
    }

    /**
     * Update the specified category.
     * 
     * Validates: Requirements 9.2, 9.4
     *
     * @param UpdateCategoryRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validated();

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Category updated successfully',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                ],
            ]);
        }

        // Return redirect for web requests
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно обновлена!');
    }

    /**
     * Remove the specified category.
     * 
     * Validates: Requirements 9.3
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        // Check if category has products
        if ($category->products()->count() > 0) {
            // Return JSON for API requests
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Cannot delete category with existing products',
                ], 400);
            }

            // Return redirect for web requests
            return redirect()->route('admin.categories.index')
                ->with('error', 'Невозможно удалить категорию с существующими товарами!');
        }

        $category->delete();

        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Category deleted successfully',
            ]);
        }

        // Return redirect for web requests
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно удалена!');
    }
}
