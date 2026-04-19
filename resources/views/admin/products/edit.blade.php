@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<style>
    .form-container {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        max-width: 800px;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #333;
    }
    
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        font-family: inherit;
    }
    
    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }
    
    .current-image {
        max-width: 200px;
        border-radius: 4px;
        margin-top: 0.5rem;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>

<div style="margin-bottom: 2rem;">
    <a href="{{ route('admin.products.index') }}" style="color: #c94b8c; text-decoration: none;">← Back to Products</a>
</div>

<div class="form-container">
    <h2 style="margin-bottom: 1.5rem;">Edit Product</h2>
    
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Product Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" required>{{ old('description', $product->description) }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="category_id">Category *</label>
            <select id="category_id" name="category_id" required>
                <option value="">Select a category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="price">Price ($) *</label>
            <input type="number" id="price" name="price" step="0.01" min="0.01" value="{{ old('price', $product->price) }}" required>
        </div>
        
        <div class="form-group">
            <label for="stock_quantity">Stock Quantity *</label>
            <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
        </div>
        
        <div class="form-group">
            <label for="image">Product Image (JPEG, PNG, GIF, WebP - Max 5MB)</label>
            @if($product->image_path)
                <div style="margin-bottom: 0.5rem;">
                    <p style="font-size: 0.875rem; color: #666;">Current image:</p>
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="current-image">
                </div>
            @endif
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
            <p style="font-size: 0.875rem; color: #666; margin-top: 0.25rem;">Leave empty to keep current image</p>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
