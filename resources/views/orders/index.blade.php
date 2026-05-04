@extends('layouts.app')

@section('title', 'Мои заказы')

@section('content')
<style>
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .orders-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .no-orders {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .no-orders h2 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #666;
    }
    
    .order-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: box-shadow 0.3s;
    }
    
    .order-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }
    
    .order-number {
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .order-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-processing {
        background-color: #cfe2ff;
        color: #084298;
    }
    
    .status-completed {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    
    .status-cancelled {
        background-color: #f8d7da;
        color: #842029;
    }
    
    .order-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
    }
    
    .info-label {
        font-size: 0.875rem;
        color: #666;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-weight: 500;
    }
    
    .order-items {
        margin-bottom: 1rem;
    }
    
    .order-items-title {
        font-size: 0.875rem;
        color: #666;
        margin-bottom: 0.5rem;
    }
    
    .order-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.5rem;
        background-color: #f8f9fa;
        border-radius: 4px;
        margin-bottom: 0.5rem;
    }
    
    .order-item-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        background-color: #e0e0e0;
    }
    
    .order-item-details {
        flex: 1;
    }
    
    .order-item-name {
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .order-item-quantity {
        font-size: 0.875rem;
        color: #666;
    }
    
    .order-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }
    
    .order-total {
        font-size: 1.5rem;
        font-weight: 700;
        color: #c94b8c;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }
    
    .btn-primary {
        background-color: #c94b8c;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #a83d73;
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
    
    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .order-info {
            grid-template-columns: 1fr;
        }
        
        .order-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>

<div class="orders-container">
    <div class="page-header">
        <h1>Мои заказы</h1>
        <p>Просмотр и отслеживание истории заказов</p>
    </div>

    @if($orders->count() > 0)
        @foreach($orders as $order)
            <div class="order-card">
                <div class="order-header">
                    <div class="order-number">Заказ №{{ $order->order_number }}</div>
                    <div class="order-status status-{{ $order->status }}">
                        @if($order->status == 'pending') Ожидает
                        @elseif($order->status == 'processing') Обрабатывается
                        @elseif($order->status == 'completed') Выполнен
                        @elseif($order->status == 'cancelled') Отменен
                        @else {{ ucfirst($order->status) }}
                        @endif
                    </div>
                </div>
                
                <div class="order-info">
                    <div class="info-item">
                        <span class="info-label">Дата заказа</span>
                        <span class="info-value">{{ $order->created_at->format('d.m.Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Имя клиента</span>
                        <span class="info-value">{{ $order->customer_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $order->customer_email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Телефон</span>
                        <span class="info-value">{{ $order->customer_phone }}</span>
                    </div>
                </div>
                
                <div class="order-items">
                    <div class="order-items-title">Товары ({{ $order->orderItems->count() }})</div>
                    @foreach($order->orderItems->take(3) as $item)
                        <div class="order-item">
                            @if($item->product && $item->product->image_path)
                                <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product_name }}" class="order-item-image">
                            @else
                                <div class="order-item-image" style="display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #999;">
                                    —
                                </div>
                            @endif
                            <div class="order-item-details">
                                <div class="order-item-name">{{ $item->product_name }}</div>
                                <div class="order-item-quantity">Кол-во: {{ $item->quantity }} × {{ format_price($item->price) }}</div>
                            </div>
                        </div>
                    @endforeach
                    @if($order->orderItems->count() > 3)
                        <div style="font-size: 0.875rem; color: #666; margin-top: 0.5rem;">
                            + ещё {{ $order->orderItems->count() - 3 }} товаров
                        </div>
                    @endif
                </div>
                
                <div class="order-footer">
                    <div class="order-total">Итого: {{ format_price($order->total_amount) }}</div>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">
                        Подробнее
                    </a>
                </div>
            </div>
        @endforeach
        
        <!-- Pagination -->
        <div class="pagination">
            {{ $orders->links() }}
        </div>
    @else
        <div class="no-orders">
            <h2>Заказов пока нет</h2>
            <p>Начните покупки, чтобы создать свой первый заказ!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary" style="margin-top: 1rem;">
                Просмотреть товары
            </a>
        </div>
    @endif
</div>
@endsection
