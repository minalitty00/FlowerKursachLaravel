<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->user = User::factory()->create(['role' => 'user']);
        $this->admin = User::factory()->create(['role' => 'admin']);

        // Create test product
        $category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $category->id,
            'stock_quantity' => 10,
            'price' => 100.00
        ]);
    }

    /** @test */
    public function user_can_create_order_from_cart(): void
    {
        // Add item to cart
        $this->actingAs($this->user, 'web')
            ->postJson('/api/cart/add', [
                'product_id' => $this->product->id,
                'quantity' => 2
            ]);

        // Create order
        $response = $this->actingAs($this->user, 'web')
            ->postJson('/api/orders', [
                'customer_name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+1234567890',
                'address' => '123 Main St, City, Country'
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'order' => [
                    'id',
                    'order_number',
                    'total_amount',
                    'status',
                    'customer_name',
                    'customer_email',
                    'customer_phone',
                    'delivery_address',
                    'order_items'
                ]
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'status' => 'pending'
        ]);
    }

    /** @test */
    public function user_can_view_their_orders(): void
    {
        // Create orders for user
        Order::factory()->count(2)->create(['user_id' => $this->user->id]);
        
        // Create order for another user
        $otherUser = User::factory()->create();
        Order::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user, 'web')
            ->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    /** @test */
    public function admin_can_view_all_orders(): void
    {
        // Create orders for different users
        Order::factory()->count(2)->create(['user_id' => $this->user->id]);
        $otherUser = User::factory()->create();
        Order::factory()->count(3)->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->admin, 'web')
            ->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    /** @test */
    public function user_can_view_their_order_details(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'web')
            ->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'order_number',
                'total_amount',
                'status'
            ]);
    }

    /** @test */
    public function user_cannot_view_other_users_order(): void
    {
        $otherUser = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user, 'web')
            ->getJson("/api/orders/{$order->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_view_any_order(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->admin, 'web')
            ->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_update_order_status(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->putJson("/api/orders/{$order->id}/status", [
                'status' => 'processing'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Order status updated successfully'
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing'
        ]);
    }

    /** @test */
    public function non_admin_cannot_update_order_status(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->user, 'web')
            ->putJson("/api/orders/{$order->id}/status", [
                'status' => 'processing'
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function order_status_validation_rejects_invalid_status(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->putJson("/api/orders/{$order->id}/status", [
                'status' => 'invalid_status'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    /** @test */
    public function store_order_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user, 'web')
            ->postJson('/api/orders', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'customer_name',
                'email',
                'phone',
                'address'
            ]);
    }

    /** @test */
    public function store_order_validates_email_format(): void
    {
        $response = $this->actingAs($this->user, 'web')
            ->postJson('/api/orders', [
                'customer_name' => 'John Doe',
                'email' => 'invalid-email',
                'phone' => '+1234567890',
                'address' => '123 Main St'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
