@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<style>
    .order-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
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
    
    .order-detail {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 2rem;
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #eee;
    }
    
    .order-number {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .order-status {
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 600;
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
    
    .order-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #333;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
    }
    
    .info-label {
        font-size: 0.875rem;
        color: #666;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        font-size: 1rem;
        font-weight: 500;
    }
    
    .order-items-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .order-items-table th {
        text-align: left;
        padding: 1rem;
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .order-items-table td {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .item-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        background-color: #f0f0f0;
    }
    
    .item-name {
        font-weight: 500;
    }
    
    .item-name a {
        color: #333;
        text-decoration: none;
    }
    
    .item-name a:hover {
        color: #c94b8c;
    }
    
    .order-summary {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #eee;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 1.125rem;
    }
    
    .summary-row.total {
        font-size: 1.75rem;
        font-weight: 700;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid #eee;
    }
    
    .summary-row.total .amount {
        color: #c94b8c;
    }
    
    .order-actions {
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
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .order-items-table {
            font-size: 0.875rem;
        }
        
        .order-items-table th,
        .order-items-table td {
            padding: 0.75rem 0.5rem;
        }
        
        .item-image {
            width: 50px;
            height: 50px;
        }
        
        .order-actions {
            flex-direction: column;
        }
    }
</style>

<div class="order-container">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> / 
        <a href="{{ route('orders.index') }}">My Orders</a> / 
        Order #{{ $order->order_number }}
    </div>
    
    <div class="page-header">
        <h1>Order Details</h1>
    </div>

    <div class="order-detail">
        <div class="order-header">
            <div class="order-number">Order #{{ $order->order_number }}</div>
            <div class="order-status status-{{ $order->status }}">
                {{ ucfirst($order->status) }}
            </div>
        </div>
        
        <!-- Order Information -->
        <div class="order-section">
            <h2 class="section-title">Order Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Order Date</span>
                    <span class="info-value">{{ $order->created_at->format('F d, Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Order Time</span>
                    <span class="info-value">{{ $order->created_at->format('h:i A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Order Status</span>
                    <span class="info-value">{{ ucfirst($order->status) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Customer Information -->
        <div class="order-section">
            <h2 class="section-title">Customer Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Name</span>
                    <span class="info-value">{{ $order->customer_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $order->customer_email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ $order->customer_phone }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Delivery Address</span>
                    <span class="info-value">{{ $order->delivery_address }}</span>
                </div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="order-section">
            <h2 class="section-title">Order Items</h2>
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    @if($item->product && $item->product->image_path)
                                        <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product_name }}" class="item-image">
                                    @else
                                        <div class="item-image" style="display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                            🌸
                                        </div>
                                    @endif
                                    <div class="item-name">
                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product_id) }}">
                                                {{ $item->product_name }}
                                            </a>
                                        @else
                                            {{ $item->product_name }}
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Order Summary -->
        <div class="order-summary">
            <div class="summary-row total">
                <span>Total Amount:</span>
                <span class="amount">${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="order-actions">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                Back to Orders
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
