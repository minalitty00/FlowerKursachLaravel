<?php

namespace Tests\Unit\Services;

use App\Exceptions\EmptyCartException;
use App\Exceptions\InsufficientStockException;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    private OrderService $orderService;
    private CartService $cartService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->cartService = new CartService();
        $this->orderService = new OrderService($this->cartService);
        $this->user = User::factory()->create();
    }

    public function test_create_order_throws_exception_when_cart_is_empty(): void
    {
        $this->expectException(EmptyCartException::class);
        
        $this->orderService->createOrder(
            $this->user->id,
            'John Doe',
            'john@example.com',
            '+1234567890',
            '123 Main St'
        );
    }

    public function test_create_order_throws_exception_when_insufficient_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);
        
        $this->actingAs($this->user);
        $this->cartService->addItem($product->id, 10);
        
        $this->expectException(InsufficientStockException::class);
        
        $this->orderService->createOrder(
            $this->user->id,
            'John Doe',
            'john@example.com',
            '+1234567890',
            '123 Main St'
        );
    }

    public function test_create_order_successfully_creates_order_and_decreases_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10, 'price' => 25.50]);
        
        $this->actingAs($this->user);
        $this->cartService->addItem($product->id, 3);
        
        $order = $this->orderService->createOrder(
            $this->user->id,
            'John Doe',
            'john@example.com',
            '+1234567890',
            '123 Main St'
        );
        
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($this->user->id, $order->user_id);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals(76.50, $order->total_amount);
        $this->assertStringStartsWith('ORD-', $order->order_number);
        
        // Check order items
        $this->assertCount(1, $order->orderItems);
        $orderItem = $order->orderItems->first();
        $this->assertEquals($product->id, $orderItem->product_id);
        $this->assertEquals(3, $orderItem->quantity);
        $this->assertEquals(25.50, $orderItem->price);
        $this->assertEquals(76.50, $orderItem->subtotal);
        
        // Check stock decreased
        $product->refresh();
        $this->assertEquals(7, $product->stock_quantity);
        
        // Check cart cleared
        $this->assertEmpty($this->cartService->getItems());
    }

    public function test_generate_order_number_has_correct_format(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);
        
        $this->actingAs($this->user);
        $this->cartService->addItem($product->id, 1);
        
        $order = $this->orderService->createOrder(
            $this->user->id,
            'John Doe',
            'john@example.com',
            '+1234567890',
            '123 Main St'
        );
        
        $this->assertMatchesRegularExpression('/^ORD-\d{8}-\d{5}$/', $order->order_number);
    }

    public function test_update_order_status_successfully(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);
        
        $updatedOrder = $this->orderService->updateOrderStatus($order->id, 'processing');
        
        $this->assertEquals('processing', $updatedOrder->status);
    }

    public function test_update_order_status_throws_exception_for_completed_order(): void
    {
        $order = Order::factory()->create(['status' => 'completed']);
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot change status of completed order');
        
        $this->orderService->updateOrderStatus($order->id, 'cancelled');
    }

    public function test_update_order_status_throws_exception_for_cancelled_order(): void
    {
        $order = Order::factory()->create(['status' => 'cancelled']);
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot change status of cancelled order');
        
        $this->orderService->updateOrderStatus($order->id, 'processing');
    }

    public function test_calculate_total_returns_correct_sum(): void
    {
        $cartItems = [
            ['subtotal' => 25.50],
            ['subtotal' => 30.00],
            ['subtotal' => 15.75],
        ];
        
        $total = $this->orderService->calculateTotal($cartItems);
        
        $this->assertEquals(71.25, $total);
    }
}
