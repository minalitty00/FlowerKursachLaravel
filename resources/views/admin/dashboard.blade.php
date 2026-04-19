@extends('layouts.admin')

@section('title', 'Панель управления')

@section('content')
<style>
    .dashboard-header {
        margin-bottom: 2rem;
    }
    
    .dashboard-header h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .stat-card h3 {
        font-size: 0.875rem;
        color: #666;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-card .value {
        font-size: 2rem;
        font-weight: 700;
        color: #c94b8c;
    }
    
    .recent-orders {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .recent-orders h2 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .orders-table th,
    .orders-table td {
        padding: 0.75rem;
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
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .orders-table {
            font-size: 0.875rem;
        }
        
        .orders-table th,
        .orders-table td {
            padding: 0.5rem;
        }
    }
</style>

<div class="dashboard-header">
    <h1>Панель управления</h1>
    <p>Добро пожаловать в админ-панель. Вот обзор магазина Флора.</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>Всего товаров</h3>
        <div class="value">{{ $stats['total_products'] }}</div>
    </div>
    
    <div class="stat-card">
        <h3>Всего заказов</h3>
        <div class="value">{{ $stats['total_orders'] }}</div>
    </div>
    
    <div class="stat-card">
        <h3>Всего пользователей</h3>
        <div class="value">{{ $stats['total_users'] }}</div>
    </div>
    
    <div class="stat-card">
        <h3>Всего категорий</h3>
        <div class="value">{{ $stats['total_categories'] }}</div>
    </div>
    
    <div class="stat-card">
        <h3>Ожидающие заказы</h3>
        <div class="value">{{ $stats['pending_orders'] }}</div>
    </div>
    
    <div class="stat-card">
        <h3>Выполненные заказы</h3>
        <div class="value">{{ $stats['completed_orders'] }}</div>
    </div>
    
    <div class="stat-card">
        <h3>Общая выручка</h3>
        <div class="value">{{ format_price($stats['total_revenue']) }}</div>
    </div>
</div>

<!-- Recent Orders -->
<div class="recent-orders">
    <h2>Последние заказы</h2>
    
    @if($recentOrders->count() > 0)
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Заказ №</th>
                    <th>Клиент</th>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->created_at->format('d.m.Y') }}</td>
                        <td>{{ format_price($order->total_amount) }}</td>
                        <td>
                            <span class="status-badge status-{{ $order->status }}">
                                @if($order->status == 'pending') Ожидает
                                @elseif($order->status == 'processing') Обрабатывается
                                @elseif($order->status == 'completed') Выполнен
                                @elseif($order->status == 'cancelled') Отменен
                                @else {{ ucfirst($order->status) }}
                                @endif
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" style="color: #c94b8c; text-decoration: none;">
                                Просмотр
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #666; text-align: center; padding: 2rem;">Заказов пока нет</p>
    @endif
</div>
@endsection
