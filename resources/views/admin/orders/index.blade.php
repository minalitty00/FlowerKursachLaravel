@extends('layouts.admin')

@section('title', 'Manage Orders')

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
    }
    
    .btn-view:hover {
        opacity: 0.8;
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

<h2 style="margin-bottom: 2rem;">Manage Orders</h2>

<div class="orders-table-container">
    @if($orders->count() > 0)
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->customer_email }}</td>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="status-badge status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-view">View Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="padding: 2rem; text-align: center; color: #666;">No orders found.</p>
    @endif
</div>
@endsection
