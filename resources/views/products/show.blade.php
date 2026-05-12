@extends('layouts.app')

@section('title', $product->name)

@section('content')
<style>
    .product-detail {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    /* Carousel Styles */
    .carousel-container {
        position: relative;
    }
    
    .carousel-main-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 8px;
        background-color: #f0f0f0;
    }
    
    .carousel-thumbnails {
        display: flex;
        gap: 10px;
        margin-top: 1rem;
        justify-content: center;
    }
    
    .carousel-thumb {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
        border: 3px solid transparent;
        transition: border-color 0.3s, transform 0.3s;
    }
    
    .carousel-thumb:hover {
        transform: scale(1.05);
    }
    
    .carousel-thumb.active {
        border-color: #c94b8c;
    }
    
    .carousel-thumb-placeholder {
        width: 80px;
        height: 80px;
        background-color: #f0f0f0;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        cursor: pointer;
    }
    
    .carousel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.9);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    
    .carousel-nav:hover {
        background: #c94b8c;
        color: white;
    }
    
    .carousel-prev {
        left: 10px;
    }
    
    .carousel-next {
        right: 10px;
    }
    
    .carousel-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 1rem;
    }
    
    .carousel-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #ddd;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .carousel-dot.active {
        background-color: #c94b8c;
    }
    
    .no-image {
        width: 100%;
        height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        background-color: #f0f0f0;
        border-radius: 8px;
    }
    
    .product-details {
        display: flex;
        flex-direction: column;
    }
    
    .breadcrumb {
        font-size: 0.875rem;
        color: #666;
        margin-bottom: 1rem;
    }
    
    .breadcrumb a {
        color: #c94b8c;
        text-decoration: none;
    }
    
    .product-category {
        color: #c94b8c;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .product-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .product-price {
        font-size: 2.5rem;
        font-weight: 700;
        color: #c94b8c;
        margin-bottom: 1rem;
    }
    
    .product-stock {
        font-size: 1rem;
        color: #666;
        margin-bottom: 2rem;
    }
    
    .product-stock.in-stock {
        color: #4caf50;
    }
    
    .product-stock.out-of-stock {
        color: #f44336;
    }
    
    .product-description {
        font-size: 1rem;
        line-height: 1.8;
        color: #666;
        margin-bottom: 2rem;
    }
    
    .add-to-cart-form {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .quantity-input {
        width: 100px;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    
    .btn {
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .btn-primary {
        background-color: #c94b8c;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #a83d73;
    }
    
    .btn-secondary {
        background-color: #f0f0f0;
        color: #333;
    }
    
    .btn-secondary:hover {
        background-color: #e0e0e0;
    }
    
    .btn:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }
    
    .product-meta {
        border-top: 1px solid #eee;
        padding-top: 2rem;
        margin-top: auto;
    }
    
    .meta-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .meta-label {
        color: #666;
    }
    
    .meta-value {
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .product-detail {
            grid-template-columns: 1fr;
            gap: 2rem;
            padding: 1.5rem;
        }
        
        .carousel-main-image,
        .no-image {
            height: 300px;
        }
        
        .product-title {
            font-size: 1.5rem;
        }
        
        .product-price {
            font-size: 2rem;
        }
        
        .add-to-cart-form {
            flex-direction: column;
        }
        
        .quantity-input {
            width: 100%;
        }
        
        .carousel-thumb {
            width: 60px;
            height: 60px;
        }
    }
</style>

<div class="product-detail">
    <div class="carousel-container">
        @php
            $allImages = $product->all_images;
            $hasImages = count($allImages) > 0;
        @endphp
        
        @if($hasImages)
            <!-- Main Image -->
            <img id="mainImage" src="{{ $allImages[0] }}" alt="{{ $product->name }}" class="carousel-main-image">
            
            <!-- Navigation Arrows -->
            <button class="carousel-nav carousel-prev" onclick="changeSlide(-1)">❮</button>
            <button class="carousel-nav carousel-next" onclick="changeSlide(1)">❯</button>
            
            <!-- Thumbnails -->
            <div class="carousel-thumbnails">
                @foreach($allImages as $index => $image)
                    <img src="{{ $image }}" 
                         alt="{{ $product->name }}" 
                         class="carousel-thumb {{ $index === 0 ? 'active' : '' }}"
                         onclick="goToSlide({{ $index }})">
                @endforeach
            </div>
            
            <!-- Dots -->
            <div class="carousel-dots">
                @foreach($allImages as $index => $image)
                    <div class="carousel-dot {{ $index === 0 ? 'active' : '' }}" 
                         onclick="goToSlide({{ $index }})"></div>
                @endforeach
            </div>
            
            <script>
                let currentSlide = 0;
                const images = @json($allImages);
                
                function goToSlide(index) {
                    currentSlide = index;
                    document.getElementById('mainImage').src = images[currentSlide];
                    updateActiveClasses();
                }
                
                function changeSlide(direction) {
                    currentSlide += direction;
                    
                    if (currentSlide < 0) {
                        currentSlide = images.length - 1;
                    } else if (currentSlide >= images.length) {
                        currentSlide = 0;
                    }
                    
                    document.getElementById('mainImage').src = images[currentSlide];
                    updateActiveClasses();
                }
                
                function updateActiveClasses() {
                    // Update thumbnails
                    document.querySelectorAll('.carousel-thumb').forEach((thumb, index) => {
                        thumb.classList.toggle('active', index === currentSlide);
                    });
                    
                    // Update dots
                    document.querySelectorAll('.carousel-dot').forEach((dot, index) => {
                        dot.classList.toggle('active', index === currentSlide);
                    });
                }
                
                // Keyboard navigation
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowLeft') {
                        changeSlide(-1);
                    } else if (e.key === 'ArrowRight') {
                        changeSlide(1);
                    }
                });
            </script>
        @else
            <div class="no-image">
                🌸
            </div>
        @endif
    </div>
    
    <div class="product-details">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Главная</a> / 
            <a href="{{ route('products.index') }}">Товары</a> / 
            {{ $product->name }}
        </div>
        
        <div class="product-category">{{ $product->category->name }}</div>
        <h1 class="product-title">{{ $product->name }}</h1>
        
        <div class="product-price">{{ format_price($product->price) }}</div>
        
        @if($product->stock_quantity > 0)
            <div class="product-stock in-stock">
                ✓ В наличии ({{ $product->stock_quantity }} доступно)
            </div>
        @else
            <div class="product-stock out-of-stock">
                ✗ Нет в наличии
            </div>
        @endif
        
        <div class="product-description">
            {{ $product->description }}
        </div>
        
        @if($product->stock_quantity > 0)
            <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input 
                    type="number" 
                    name="quantity" 
                    value="1" 
                    min="1" 
                    max="{{ $product->stock_quantity }}" 
                    class="quantity-input"
                    required
                >
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    Добавить в корзину
                </button>
            </form>
        @else
            <button class="btn btn-primary" disabled style="width: 100%;">
                Нет в наличии
            </button>
        @endif
        
        <a href="{{ route('products.index') }}" class="btn btn-secondary" style="width: 100%;">
            Продолжить покупки
        </a>
        
        <div class="product-meta">
            <div class="meta-item">
                <span class="meta-label">Категория:</span>
                <span class="meta-value">{{ $product->category->name }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">ID товара:</span>
                <span class="meta-value">#{{ $product->id }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Добавлен:</span>
                <span class="meta-value">{{ $product->created_at->format('d.m.Y') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection