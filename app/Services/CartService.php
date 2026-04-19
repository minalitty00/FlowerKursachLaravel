<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const CART_SESSION_KEY = 'cart';

    /**
     * Add product to cart
     */
    public function addItem(int $productId, int $quantity): void
    {
        $product = Product::findOrFail($productId);
        
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }
        
        $this->saveCart($cart);
    }
    
    /**
     * Update item quantity in cart
     * 
     * @throws \Exception
     */
    public function updateQuantity(int $productId, int $quantity): void
    {
        $product = Product::findOrFail($productId);
        
        if ($quantity > $product->stock_quantity) {
            throw new \Exception('Insufficient stock. Available: ' . $product->stock_quantity);
        }
        
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            $this->saveCart($cart);
        }
    }
    
    /**
     * Remove item from cart
     */
    public function removeItem(int $productId): void
    {
        $cart = $this->getCart();
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->saveCart($cart);
        }
    }
    
    /**
     * Get all cart items with product details
     */
    public function getItems(): array
    {
        $cart = $this->getCart();
        $items = [];
        
        foreach ($cart as $productId => $cartItem) {
            $product = Product::find($productId);
            
            if ($product) {
                $items[] = [
                    'product_id' => $productId,
                    'product' => $product,
                    'quantity' => $cartItem['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $cartItem['quantity'],
                ];
            }
        }
        
        return $items;
    }
    
    /**
     * Calculate cart total
     */
    public function getTotal(): float
    {
        $items = $this->getItems();
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }
        
        return round($total, 2);
    }
    
    /**
     * Clear cart
     */
    public function clear(): void
    {
        Session::forget(self::CART_SESSION_KEY);
    }
    
    /**
     * Get cart from session
     */
    private function getCart(): array
    {
        return Session::get(self::CART_SESSION_KEY, []);
    }
    
    /**
     * Save cart to session
     */
    private function saveCart(array $cart): void
    {
        Session::put(self::CART_SESSION_KEY, $cart);
    }
}
