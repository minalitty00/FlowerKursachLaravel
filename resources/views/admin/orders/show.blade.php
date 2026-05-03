@extends('layouts.admin')

@section('title', 'Детали заказа')

@section('content')
<style>
    .order-details {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #eee;
    }
    
    .order-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .info-block h3 {
        font-size: 0.875rem;
        color: #666;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-block p {
        font-size: 1rem;
        color: #333;
        margin: 0.25rem 0;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-size: 1rem;
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
    
    .status-form {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #eee;
    }
    
    .status-form select {
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        margin-right: 1rem;
    }
    
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    
    .items-table th,
    .items-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    .items-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }
    
    .items-table tfoot td {
        font-weight: 600;
        font-size: 1.125rem;
        padding-top: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .order-info-grid {
            grid-template-columns: 1fr;
        }
        
        .items-table {
            font-size: 0.875rem;
        }
        
        .items-table th,
        .items-table td {
            padding: 0.5rem;
        }
    }
</style>

<div style="margin-bottom: 2rem;">
    <a href="{{ route('admin.orders.index') }}" style="color: #c94b8c; text-decoration: none;">← Назад к заказам</a>
</div>

<div class="order-details">
    <div class="order-header">
        <div>
            <h2>Заказ {{ $order->order_number }}</h2>
            <p style="color: #666; margin-top: 0.5rem;">Размещен {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y в H:i') }}</p>
        </div>
        <span class="status-badge status-{{ $order->status }}">
            @if($order->status == 'pending') Ожидает
            @elseif($order->status == 'processing') В обработке
            @elseif($order->status == 'completed') Завершён
            @elseif($order->status == 'cancelled') Отменён
            @else {{ ucfirst($order->status) }}
            @endif
        </span>
    </div>
    
    <div class="order-info-grid">
        <div class="info-block">
            <h3>Информация о клиенте</h3>
            <p><strong>Имя:</strong> {{ $order->customer_name }}</p>
            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
            <p><strong>Телефон:</strong> {{ $order->customer_phone }}</p>
        </div>
        
        <div class="info-block">
            <h3>Адрес доставки</h3>
            <p>{{ $order->delivery_address }}</p>
        </div>
        
        <div class="info-block">
            <h3>Итоги заказа</h3>
            <p><strong>Общая сумма:</strong> {{ format_price($order->total_amount) }}</p>
            <p><strong>Товаров:</strong> {{ $order->orderItems->count() }}</p>
        </div>
    </div>
    
    <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Товары заказа</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>Товар</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ format_price($item->price) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ format_price($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;">Итого:</td>
                <td>{{ format_price($order->total_amount) }}</td>
            </tr>
        </tfoot>
    </table>
    
    <div class="status-form">
        <h3 style="margin-bottom: 1rem;">Обновить статус заказа</h3>
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <select name="status" required>
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Ожидание</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>В обработке</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Завершён</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Отменён</option>
            </select>
            
            <button type="submit" class="btn-primary">Обновить статус</button>
        </form>
    </div>
</div>
@endsection
