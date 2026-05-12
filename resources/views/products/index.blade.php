@extends('layouts.app')

@section('title', 'Товары')

@section('content')
<style>
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .filters select,
    .filters input {
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        flex: 1;
        min-width: 200px;
        background-color: white;
    }
    
    .filters button {
        padding: 0.75rem 2rem;
        background-color: #c94b8c;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .filters button:hover {
        background-color: #a83d73;
    }
    
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }
    
    .product-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .product-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        background-color: #f0f0f0;
    }
    
    .product-info {
        padding: 1.5rem;
    }
    
    .product-category {
        color: #c94b8c;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .product-name {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .product-description {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #c94b8c;
    }
    
    .product-stock {
        font-size: 0.875rem;
        color: #666;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }
    
    .pagination a,
    .pagination span {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #333;
    }
    
    .pagination .active {
        background-color: #c94b8c;
        color: white;
        border-color: #c94b8c;
    }
    
    .no-products {
        text-align: center;
        padding: 3rem;
        color: #666;
    }
    
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .filters {
            flex-direction: column;
        }
        
        .filters select,
        .filters input {
            width: 100%;
        }
    }
</style>

<div class="page-header">
    <h1>Наши товары</h1>
    <p>Просмотрите нашу коллекцию прекрасных цветов</p>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('products.index') }}" class="filters">
    <select name="category_id" id="category_id">
        <option value="">Все категории</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                {{ $category->name }} ({{ $category->products_count }})
            </option>
        @endforeach
    </select>
    
    <input 
        type="text" 
        name="search" 
        placeholder="Поиск товаров..." 
        value="{{ $searchQuery }}"
    >
    
    <button type="submit">Найти</button>
</form>

<!-- Product Grid -->
@if($products->count() > 0)
    <div class="product-grid">
        @foreach($products as $product)
            <a href="{{ route('products.show', $product->id) }}" class="product-card">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
                @else
                    <div class="product-image" style="display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                        🌸
                    </div>
                @endif
                
                <div class="product-info">
                    <div class="product-category">{{ $product->category->name }}</div>
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="product-description">{{ $product->description }}</p>
                    
                    <div class="product-footer">
                        <span class="product-price">{{ format_price($product->price) }}</span>
                        <span class="product-stock">В наличии: {{ $product->stock_quantity }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="pagination">
        {{ $products->links() }}
    </div>
@else
    <div class="no-products">
        <h2>Товары не найдены</h2>
        <p>Попробуйте изменить критерии поиска или фильтра</p>
    </div>
@endif
@endsection
