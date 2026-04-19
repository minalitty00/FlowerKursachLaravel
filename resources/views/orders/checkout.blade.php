@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<style>
    .checkout-container {
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
    
    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 2rem;
    }
    
    .checkout-form {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 2rem;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #333;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #c94b8c;
    }
    
    .form-input.error {
        border-color: #f44336;
    }
    
    .error-message {
        color: #f44336;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .order-summary {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 2rem;
        height: fit-content;
        position: sticky;
        top: 100px;
    }
    
    .summary-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #333;
    }
    
    .summary-items {
        margin-bottom: 1.5rem;
    }
    
    .summary-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }
    
    .summary-item:last-child {
        border-bottom: none;
    }
    
    .summary-item-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        background-color: #f0f0f0;
    }
    
    .summary-item-details {
        flex: 1;
    }
    
    .summary-item-name {
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }
    
    .summary-item-quantity {
        font-size: 0.875rem;
        color: #666;
    }
    
    .summary-item-price {
        font-weight: 600;
        color: #c94b8c;
    }
    
    .summary-total {
        padding-top: 1.5rem;
        border-top: 2px solid #eee;
        margin-bottom: 1.5rem;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .total-amount {
        color: #c94b8c;
    }
    
    .btn {
        width: 100%;
        padding: 1rem;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s;
        font-weight: 600;
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
        margin-top: 1rem;
    }
    
    .btn-secondary:hover {
        background-color: #e0e0e0;
    }
    
    @media (max-width: 768px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }
        
        .order-summary {
            position: static;
        }
    }
</style>

<div class="checkout-container">
    <div class="page-header">
        <h1>Checkout</h1>
        <p>Complete your order</p>
    </div>

    <div class="checkout-grid">
        <!-- Checkout Form -->
        <div class="checkout-form">
            <form action="{{ route('orders.processCheckout') }}" method="POST">
                @csrf
                
                <div class="form-section">
                    <h2 class="section-title">Customer Information</h2>
                    
                    <div class="form-group">
                        <label for="customer_name" class="form-label">Full Name *</label>
                        <input 
                            type="text" 
                            id="customer_name" 
                            name="customer_name" 
                            class="form-input @error('customer_name') error @enderror"
                            value="{{ old('customer_name', $user->name) }}"
                            required
                        >
                        @error('customer_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input @error('email') error @enderror"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-input @error('phone') error @enderror"
                            value="{{ old('phone') }}"
                            required
                        >
                        @error('phone')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-section">
                    <h2 class="section-title">Delivery Information</h2>
                    
                    <div class="form-group">
                        <label for="address" class="form-label">Delivery Address *</label>
                        <textarea 
                            id="address" 
                            name="address" 
                            rows="4" 
                            class="form-input @error('address') error @enderror"
                            required
                        >{{ old('address') }}</textarea>
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    Place Order
                </button>
            </form>
        </div>
        
        <!-- Order Summary -->
        <div class="order-summary">
            <h2 class="summary-title">Order Summary</h2>
            
            <div class="summary-items">
                @foreach($cartItems as $item)
                    <div class="summary-item">
                        @if($item['product']->image_path)
                            <img src="{{ asset('storage/' . $item['product']->image_path) }}" alt="{{ $item['product']->name }}" class="summary-item-image">
                        @else
                            <div class="summary-item-image" style="display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                🌸
                            </div>
                        @endif
                        
                        <div class="summary-item-details">
                            <div class="summary-item-name">{{ $item['product']->name }}</div>
                            <div class="summary-item-quantity">Qty: {{ $item['quantity'] }}</div>
                        </div>
                        
                        <div class="summary-item-price">
                            ${{ number_format($item['subtotal'], 2) }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="summary-total">
                <div class="total-row">
                    <span>Total:</span>
                    <span class="total-amount">${{ number_format($total, 2) }}</span>
                </div>
            </div>
            
            <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                Back to Cart
            </a>
        </div>
    </div>
</div>
@endsection
