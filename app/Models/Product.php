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
        'image_url',
        'image_path_2',
        'image_url_2',
        'image_path_3',
        'image_url_3',
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

    /**
     * Get the image URL (external URL or local path).
     */
    public function getImageUrlAttribute(): ?string
    {
        // Получаем сырое значение image_url из базы данных
        $imageUrl = isset($this->attributes['image_url']) ? $this->attributes['image_url'] : null;
        $imagePath = $this->getAttribute('image_path');
        
        return $imageUrl ?: ($imagePath ? asset('storage/' . $imagePath) : null);
    }

    /**
     * Get the second image URL (external URL or local path).
     */
    public function getImageUrl2Attribute(): ?string
    {
        // Получаем сырое значение image_url_2 из базы данных
        $imageUrl = isset($this->attributes['image_url_2']) ? $this->attributes['image_url_2'] : null;
        $imagePath = $this->getAttribute('image_path_2');
        
        return $imageUrl ?: ($imagePath ? asset('storage/' . $imagePath) : null);
    }

    /**
     * Get the third image URL (external URL or local path).
     */
    public function getImageUrl3Attribute(): ?string
    {
        // Получаем сырое значение image_url_3 из базы данных
        $imageUrl = isset($this->attributes['image_url_3']) ? $this->attributes['image_url_3'] : null;
        $imagePath = $this->getAttribute('image_path_3');
        
        return $imageUrl ?: ($imagePath ? asset('storage/' . $imagePath) : null);
    }

    /**
     * Get all image URLs as array.
     */
    public function getAllImagesAttribute(): array
    {
        $images = [];
        
        if ($this->image_url) {
            $images[] = $this->image_url;
        }
        
        if ($this->image_url_2) {
            $images[] = $this->image_url_2;
        }
        
        if ($this->image_url_3) {
            $images[] = $this->image_url_3;
        }
        
        return $images;
    }
}
