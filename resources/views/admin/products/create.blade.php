@extends('layouts.admin')

@section('title', 'Создание товара')

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
    <a href="{{ route('admin.products.index') }}" style="color: #c94b8c; text-decoration: none;">← Назад к товарам</a>
</div>

<div class="form-container">
    <h2 style="margin-bottom: 1.5rem;">Создание нового товара</h2>
    
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="name">Название товара *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        
        <div class="form-group">
            <label for="description">Описание *</label>
            <textarea id="description" name="description" required>{{ old('description') }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="category_id">Категория *</label>
            <select id="category_id" name="category_id" required>
                <option value="">Выберите категорию</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="price">Цена *</label>
            <input type="number" id="price" name="price" step="0.01" min="0.01" value="{{ old('price') }}" required>
        </div>
        
        <div class="form-group">
            <label for="stock_quantity">Количество на складе *</label>
            <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="{{ old('stock_quantity', 0) }}" required>
        </div>
        
        <div class="form-group">
            <label for="image">Изображение товара (JPEG, PNG, GIF, WebP - макс 5МБ)</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
            <p style="font-size: 0.875rem; color: #666; margin-top: 0.25rem;">ИЛИ используйте URL изображения ниже</p>
        </div>
        
        <div class="form-group">
            <label for="image_url">URL изображения</label>
            <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg">
            <p style="font-size: 0.875rem; color: #666; margin-top: 0.25rem;">Оставьте пустым, если загружаете файл</p>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Создать товар</button>
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">Отмена</a>
        </div>
    </form>
</div>
@endsection
