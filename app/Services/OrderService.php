<?php

namespace App\Services;

use App\Exceptions\EmptyCartException;
use App\Exceptions\InsufficientStockException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        private CartService $cartService
    ) {}

    /**
     * Create a new order from cart items
     * 
     * @throws EmptyCartException
     * @throws InsufficientStockException
     */
    public function createOrder(
        int $userId,
        string $customerName,
        string $customerEmail,
        string $customerPhone,
        string $deliveryAddress,
        ?string $deliveryDate = null,
        ?string $deliveryTime = null
    ): Order {
        $cartItems = $this->cartService->getItems();
        
        if (empty($cartItems)) {
            throw new EmptyCartException();
        }

        return DB::transaction(function () use ($userId, $customerName, $customerEmail, $customerPhone, $deliveryAddress, $cartItems, $deliveryDate, $deliveryTime) {
            // Check stock availability and lock products for update
            foreach ($cartItems as $item) {
                $product = Product::where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();
                
                if (!$product) {
                    throw new \Exception("Product not found: {$item['product_id']}");
                }
                
                if ($product->stock_quantity < $item['quantity']) {
                    throw new InsufficientStockException(
                        $product->name,
                        $item['quantity'],
                        $product->stock_quantity
                    );
                }
            }

            // Calculate total
            $totalAmount = $this->calculateTotal($cartItems);

            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();

            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => $orderNumber,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'delivery_address' => $deliveryAddress,
                'delivery_date' => $deliveryDate,
                'delivery_time' => $deliveryTime,
            ]);

            // Create order items and decrease stock
            foreach ($cartItems as $item) {
                $product = Product::find($item['product_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);

                // Decrease stock atomically
                $product->decrement('stock_quantity', $item['quantity']);
            }

            // Clear cart after successful order creation
            $this->cartService->clear();

            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => $userId,
                'total_amount' => $totalAmount,
            ]);

            return $order->load('orderItems');
        });
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(int $orderId, string $status): Order
    {
        $order = Order::findOrFail($orderId);
        
        $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            throw new \Exception("Invalid status: {$status}");
        }

        $order->update(['status' => $status]);
        
        Log::info('Order status updated', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'old_status' => $order->getOriginal('status'),
            'new_status' => $status,
        ]);
        
        return $order;
    }

    /**
     * Calculate total amount for cart items
     */
    public function calculateTotal(array $cartItems): float
    {
        $total = 0;
        
        foreach ($cartItems as $item) {
            $total += $item['subtotal'];
        }
        
        return round($total, 2);
    }

    /**
     * Generate unique order number in format: ORD-YYYYMMDD-XXXXX
     */
    private function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $random = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        
        $orderNumber = "ORD-{$date}-{$random}";
        
        // Ensure uniqueness
        while (Order::where('order_number', $orderNumber)->exists()) {
            $random = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $orderNumber = "ORD-{$date}-{$random}";
        }
        
        return $orderNumber;
    }
}
