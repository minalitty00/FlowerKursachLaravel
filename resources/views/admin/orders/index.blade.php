@extends('layouts.admin')

@section('title', 'Управление заказами')

@section('content')
<style>
    .orders-table-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .orders-table th,
    .orders-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    .orders-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }
    
    .orders-table tr:hover {
        background-color: #f8f9fa;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
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
    
    .btn-view {
        background-color: #0d6efd;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        font-size: 0.875rem;
        transition: opacity 0.3s;
        margin-right: 0.5rem;
    }
    
    .btn-view:hover {
        opacity: 0.8;
    }
    
    .btn-delete {
        background-color: #dc3545;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        font-size: 0.875rem;
        transition: opacity 0.3s;
        cursor: pointer;
    }
    
    .btn-delete:hover {
        opacity: 0.8;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .orders-table {
            font-size: 0.875rem;
        }
        
        .orders-table th,
        .orders-table td {
            padding: 0.5rem;
        }
    }
</style>

<h2 style="margin-bottom: 2rem;">Управление заказами</h2>

<div class="orders-table-container">
    @if($orders->count() > 0)
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Заказ №</th>
                    <th>Клиент</th>
                    <th>Email</th>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->customer_email }}</td>
                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td>{{ format_price($order->total_amount) }}</td>
                        <td>
                            <span class="status-badge status-{{ $order->status }}">
                                @if($order->status == 'pending') Ожидает
                                @elseif($order->status == 'processing') В обработке
                                @elseif($order->status == 'completed') Завершён
                                @elseif($order->status == 'cancelled') Отменён
                                @else {{ ucfirst($order->status) }}
                                @endif
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-view">Подробнее</a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Вы уверены, что хотите удалить заказ №{{ $order->order_number }}?')">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="padding: 2rem; text-align: center; color: #666;">Заказы не найдены.</p>
    @endif
</div>
@endsection
