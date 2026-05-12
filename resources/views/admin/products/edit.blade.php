@extends('layouts.admin')

@section('title', 'Редактирование товара')

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
    
    .image-section {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .image-section h3 {
        margin-bottom: 1rem;
        color: #333;
        font-size: 1.1rem;
    }
    
    .image-preview {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    
    .preview-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
        border: 2px solid #ddd;
        display: none;
    }
    
    .preview-image.visible {
        display: block;
    }
    
    .current-images {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
        flex-wrap: wrap;
    }
    
    .current-image-item {
        position: relative;
    }
    
    .current-image-item img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
        border: 2px solid #ddd;
    }
    
    .image-number {
        position: absolute;
        top: -10px;
        left: -10px;
        background: #c94b8c;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: bold;
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
    <h2 style="margin-bottom: 1.5rem;">Редактирование товара</h2>
    
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Название товара *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
        </div>
        
        <div class="form-group">
            <label for="description">Описание *</label>
            <textarea id="description" name="description" required>{{ old('description', $product->description) }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="category_id">Категория *</label>
            <select id="category_id" name="category_id" required>
                <option value="">Выберите категорию</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="price">Цена *</label>
            <input type="number" id="price" name="price" step="0.01" min="0.01" value="{{ old('price', $product->price) }}" required>
        </div>
        
        <div class="form-group">
            <label for="stock_quantity">Количество на складе *</label>
            <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
        </div>
        
        <!-- Текущие изображения -->
        <div class="image-section">
            <h3>Текущие изображения</h3>
            <div class="current-images">
                @if($product->image_url)
                    <div class="current-image-item">
                        <div class="image-number">1</div>
                        <img src="{{ $product->image_url }}" alt="Основное изображение">
                    </div>
                @endif
                
                @if($product->image_url_2)
                    <div class="current-image-item">
                        <div class="image-number">2</div>
                        <img src="{{ $product->image_url_2 }}" alt="Второе изображение">
                    </div>
                @endif
                
                @if($product->image_url_3)
                    <div class="current-image-item">
                        <div class="image-number">3</div>
                        <img src="{{ $product->image_url_3 }}" alt="Третье изображение">
                    </div>
                @endif
                
                @if(!$product->image_url && !$product->image_url_2 && !$product->image_url_3)
                    <p style="color: #666; font-style: italic;">Изображения не загружены</p>
                @endif
            </div>
        </div>
        
        <!-- Основное изображение -->
        <div class="image-section">
            <h3>Основное изображение</h3>
            
            <div class="form-group">
                <label for="image">Загрузить новый файл (JPEG, PNG, GIF, WebP - макс 5МБ)</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewImage(this, 'preview1')">
                <p style="font-size: 0.875rem; color: #666; margin-top: 0.25rem;">ИЛИ используйте URL изображения ниже</p>
            </div>
            
            <div class="form-group">
                <label for="image_url">URL изображения</label>
                <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="https://example.com/image.jpg" oninput="previewUrl(this, 'preview1')">
                <p style="font-size: 0.875rem; color: #666; margin-top: 0.25rem;">Оставьте пустым, если загружаете файл или хотите сохранить текущее</p>
            </div>
            
            <div class="image-preview">
                <img id="preview1" class="preview-image" src="" alt="Предпросмотр">
            </div>
        </div>
        
        <!-- Второе изображение -->
        <div class="image-section">
            <h3>Второе изображение</h3>
            
            <div class="form-group">
                <label for="image_2">Загрузить новый файл (JPEG, PNG, GIF, WebP - макс 5МБ)</label>
                <input type="file" id="image_2" name="image_2" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewImage(this, 'preview2')">
                <p style="font-size: 0.875rem; color: #666; margin-top: 0.25rem;">ИЛИ используйте URL изображения ниже</p>
            </div>
            
            <div class="form-group">
                <label for="image_url_2">URL изображения</label>
                <input type="url" id="image_url_2" name="image_url_2" value="{{ old('image_url_2', $product->image_url_2) }}" placeholder="https://example.com/image2.jpg" oninput="previewUrl(this, 'preview2')">
                <p style="font-size: 0.875rem; color: #666; margin-top: 0.25rem;">Оставьте пустым, если загружаете файл или хотите сохранить текущее</p>
            </div>
            
            <div class="image-preview">
                <img id="preview2" class="preview-image" src="" alt="Предпросмотр">
            </div>
        </div>
        
        <!-- Третье изображение -->
        <div class="image-section">
            <h3>Третье изображение</h3>
            
            <div class="form-group">
                <label for="image_3">Загрузить новый файл (JPEG, PNG, GIF, WebP - макс 5МБ)</label>
                <input type="file" id="image_3" name="image_3" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewImage(this, 'preview3')">
                <p style="font-size: 0.875rem; color: #666; margin-top: 0.25rem;">ИЛИ используйте URL изображения ниже</p>
            </div>
            
            <div class="form-group">
                <label for="image_url_3">URL изображения</label>
                <input type="url" id="image_url_3" name="image_url_3" value="{{ old('image_url_3', $product->image_url_3) }}" placeholder="https://example.com/image3.jpg" oninput="previewUrl(this, 'preview3')">
                <p style="font-size: 0.875rem; color: #666; margin-top: 0.25rem;">Оставьте пустым, если загружаете файл или хотите сохранить текущее</p>
            </div>
            
            <div class="image-preview">
                <img id="preview3" class="preview-image" src="" alt="Предпросмотр">
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Обновить товар</button>
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">Отмена</a>
        </div>
    </form>
</div>

<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.add('visible');
            }
            
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.classList.remove('visible');
        }
    }
    
    function previewUrl(input, previewId) {
        const preview = document.getElementById(previewId);
        const url = input.value;
        
        if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
            preview.src = url;
            preview.classList.add('visible');
        } else {
            preview.src = '';
            preview.classList.remove('visible');
        }
    }
    
    // Инициализация предпросмотра для уже заполненных полей
    document.addEventListener('DOMContentLoaded', function() {
        const urlInputs = [
            {id: 'image_url', preview: 'preview1'},
            {id: 'image_url_2', preview: 'preview2'},
            {id: 'image_url_3', preview: 'preview3'}
        ];
        
        urlInputs.forEach(item => {
            const input = document.getElementById(item.id);
            if (input && input.value) {
                previewUrl(input, item.preview);
            }
        });
    });
</script>
@endsection