<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_path',
        'category_id',
        'stock_quantity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to only include products in stock.
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock_quantity', '>', 0);
    }

    /**
     * Scope a query to filter products by category.
     */
    public function scopeByCategory(Builder $query, ?int $categoryId): Builder
    {
        if ($categoryId === null) {
            return $query;
        }
        
        return $query->where('category_id', $categoryId);
    }
}
