@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
<style>
    .cart-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .cart-empty {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .cart-empty h2 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #666;
    }
    
    .cart-items {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .cart-item {
        display: grid;
        grid-template-columns: 100px 1fr auto;
        gap: 1.5rem;
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
        align-items: center;
    }
    
    .cart-item:last-child {
        border-bottom: none;
    }
    
    .cart-item-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
        background-color: #f0f0f0;
    }
    
    .cart-item-details {
        flex: 1;
    }
    
    .cart-item-name {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .cart-item-name a {
        color: #333;
        text-decoration: none;
    }
    
    .cart-item-name a:hover {
        color: #c94b8c;
    }
    
    .cart-item-price {
        color: #666;
        margin-bottom: 0.5rem;
    }
    
    .cart-item-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    
    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quantity-input {
        width: 80px;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-align: center;
    }
    
    .btn-update {
        padding: 0.5rem 1rem;
        background-color: #2196f3;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.875rem;
    }
    
    .btn-update:hover {
        background-color: #1976d2;
    }
    
    .btn-remove {
        padding: 0.5rem 1rem;
        background-color: #f44336;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.875rem;
    }
    
    .btn-remove:hover {
        background-color: #d32f2f;
    }
    
    .cart-item-subtotal {
        text-align: right;
    }
    
    .subtotal-label {
        font-size: 0.875rem;
        color: #666;
        display: block;
        margin-bottom: 0.25rem;
    }
    
    .subtotal-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: #c94b8c;
    }
    
    .cart-summary {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 2rem;
        margin-top: 2rem;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 1.125rem;
    }
    
    .summary-row.total {
        border-top: 2px solid #eee;
        padding-top: 1rem;
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .summary-row.total .amount {
        color: #c94b8c;
    }
    
    .cart-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .btn {
        padding: 1rem 2rem;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s;
    }
    
    .btn-primary {
        background-color: #c94b8c;
        color: white;
        flex: 1;
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
    
    @media (max-width: 768px) {
        .cart-item {
            grid-template-columns: 80px 1fr;
            gap: 1rem;
        }
        
        .cart-item-image {
            width: 80px;
            height: 80px;
        }
        
        .cart-item-subtotal {
            grid-column: 1 / -1;
            text-align: left;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        
        .cart-item-actions {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .cart-actions {
            flex-direction: column;
        }
    }
</style>

<div class="cart-container">
    <div class="page-header">
        <h1>Корзина</h1>
    </div>

    @if(empty($items))
        <div class="cart-empty">
            <h2>Ваша корзина пуста</h2>
            <p>Добавьте прекрасные цветы в корзину!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top: 1rem;">
                Просмотреть товары
            </a>
        </div>
    @else
        <div class="cart-items">
            @foreach($items as $item)
                <div class="cart-item">
                    @if($item['product']->image_url)
                        <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}" class="cart-item-image">
                    @else
                        <div class="cart-item-image" style="display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                            🌸
                        </div>
                    @endif
                    
                    <div class="cart-item-details">
                        <h3 class="cart-item-name">
                            <a href="{{ route('products.show', $item['product']->id) }}">
                                {{ $item['product']->name }}
                            </a>
                        </h3>
                        <div class="cart-item-price">
                            {{ format_price($item['price']) }} за шт.
                        </div>
                        
                        <div class="cart-item-actions">
                            <form action="{{ route('cart.update') }}" method="POST" class="quantity-controls">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                <input 
                                    type="number" 
                                    name="quantity" 
                                    value="{{ $item['quantity'] }}" 
                                    min="1" 
                                    max="{{ $item['product']->stock_quantity }}" 
                                    class="quantity-input"
                                >
                                <button type="submit" class="btn-update">Обновить</button>
                            </form>
                            
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                <button type="submit" class="btn-remove">Удалить</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="cart-item-subtotal">
                        <span class="subtotal-label">Подытог</span>
                        <span class="subtotal-amount">{{ format_price($item['subtotal']) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="cart-summary">
            <div class="summary-row total">
                <span>Итого:</span>
                <span class="amount">{{ format_price($total) }}</span>
            </div>
            
            <div class="cart-actions">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    Продолжить покупки
                </a>
                <a href="{{ route('checkout') }}" class="btn btn-primary">
                    Оформить заказ
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
