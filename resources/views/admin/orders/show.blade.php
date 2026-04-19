@extends('layouts.admin')

@section('title', 'Order Details')

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
    <a href="{{ route('admin.orders.index') }}" style="color: #c94b8c; text-decoration: none;">← Back to Orders</a>
</div>

<div class="order-details">
    <div class="order-header">
        <div>
            <h2>Order {{ $order->order_number }}</h2>
            <p style="color: #666; margin-top: 0.5rem;">Placed on {{ $order->created_at->format('F d, Y \a\t H:i') }}</p>
        </div>
        <span class="status-badge status-{{ $order->status }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>
    
    <div class="order-info-grid">
        <div class="info-block">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $order->customer_name }}</p>
            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
            <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
        </div>
        
        <div class="info-block">
            <h3>Delivery Address</h3>
            <p>{{ $order->delivery_address }}</p>
        </div>
        
        <div class="info-block">
            <h3>Order Summary</h3>
            <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
            <p><strong>Items:</strong> {{ $order->orderItems->count() }}</p>
        </div>
    </div>
    
    <h3 style="margin-top: 2rem; margin-bottom: 1rem;">Order Items</h3>
    <table class="items-table">
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
                    <td>{{ $item->product_name }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;">Total:</td>
                <td>${{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>
    
    <div class="status-form">
        <h3 style="margin-bottom: 1rem;">Update Order Status</h3>
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <select name="status" required>
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            
            <button type="submit" class="btn-primary">Update Status</button>
        </form>
    </div>
</div>
@endsection
