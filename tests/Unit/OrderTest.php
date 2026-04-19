<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_casts()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-20260419-00001',
            'total_amount' => 199.99,
            'status' => 'pending',
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_phone' => '+1234567890',
            'delivery_address' => '123 Test St',
        ]);

        $this->assertIsString($order->total_amount);
        $this->assertEquals('199.99', $order->total_amount);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $order->created_at);
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-20260419-00001',
            'total_amount' => 199.99,
            'status' => 'pending',
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_phone' => '+1234567890',
            'delivery_address' => '123 Test St',
        ]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    /** @test */
    public function completed_scope_filters_completed_orders()
    {
        $user = User::factory()->create();
        
        Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-20260419-00001',
            'total_amount' => 100.00,
            'status' => 'pending',
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_phone' => '+1234567890',
            'delivery_address' => '123 Test St',
        ]);
        
        Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-20260419-00002',
            'total_amount' => 200.00,
            'status' => 'completed',
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_phone' => '+1234567890',
            'delivery_address' => '123 Test St',
        ]);
        
        Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-20260419-00003',
            'total_amount' => 300.00,
            'status' => 'completed',
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_phone' => '+1234567890',
            'delivery_address' => '123 Test St',
        ]);

        $completedOrders = Order::completed()->get();

        $this->assertCount(2, $completedOrders);
        $this->assertTrue($completedOrders->every(fn($o) => $o->status === 'completed'));
    }

    /** @test */
    public function for_user_scope_filters_orders_by_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Order::create([
            'user_id' => $user1->id,
            'order_number' => 'ORD-20260419-00001',
            'total_amount' => 100.00,
            'status' => 'pending',
            'customer_name' => 'User 1',
            'customer_email' => 'user1@example.com',
            'customer_phone' => '+1234567890',
            'delivery_address' => '123 Test St',
        ]);
        
        Order::create([
            'user_id' => $user1->id,
            'order_number' => 'ORD-20260419-00002',
            'total_amount' => 200.00,
            'status' => 'completed',
            'customer_name' => 'User 1',
            'customer_email' => 'user1@example.com',
            'customer_phone' => '+1234567890',
            'delivery_address' => '123 Test St',
        ]);
        
        Order::create([
            'user_id' => $user2->id,
            'order_number' => 'ORD-20260419-00003',
            'total_amount' => 300.00,
            'status' => 'pending',
            'customer_name' => 'User 2',
            'customer_email' => 'user2@example.com',
            'customer_phone' => '+1234567890',
            'delivery_address' => '456 Test Ave',
        ]);

        $user1Orders = Order::forUser($user1->id)->get();
        $user2Orders = Order::forUser($user2->id)->get();

        $this->assertCount(2, $user1Orders);
        $this->assertCount(1, $user2Orders);
        $this->assertTrue($user1Orders->every(fn($o) => $o->user_id === $user1->id));
        $this->assertTrue($user2Orders->every(fn($o) => $o->user_id === $user2->id));
    }
}
