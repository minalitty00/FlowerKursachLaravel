<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_can_view_empty_cart(): void
    {
        $response = $this->getJson('/cart');

        $response->assertStatus(200)
            ->assertJson([
                'items' => [],
                'total' => 0
            ]);
    }

    public function test_can_add_product_to_cart(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 25.50,
            'stock_quantity' => 10
        ]);

        $response = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Product added to cart successfully'
            ])
            ->assertJsonStructure([
                'items' => [
                    '*' => ['product_id', 'quantity', 'price', 'subtotal']
                ],
                'total'
            ]);
    }

    public function test_add_to_cart_validates_product_id(): void
    {
        $response = $this->postJson('/cart/add', [
            'product_id' => 99999,
            'quantity' => 1
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('product_id');
    }

    public function test_add_to_cart_validates_quantity(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock_quantity' => 10
        ]);

        $response = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 0
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('quantity');
    }

    public function test_can_update_cart_item_quantity(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 15.00,
            'stock_quantity' => 20
        ]);

        // First add item to cart
        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Then update quantity
        $response = $this->putJson('/cart/update', [
            'product_id' => $product->id,
            'quantity' => 5
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Cart updated successfully'
            ]);
    }

    public function test_update_cart_rejects_quantity_exceeding_stock(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock_quantity' => 5
        ]);

        // Add item to cart
        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Try to update to quantity exceeding stock
        $response = $this->putJson('/cart/update', [
            'product_id' => $product->id,
            'quantity' => 10
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Failed to update cart'
            ]);
    }

    public function test_can_remove_product_from_cart(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock_quantity' => 10
        ]);

        // Add item to cart
        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Remove item from cart
        $response = $this->deleteJson('/cart/remove', [
            'product_id' => $product->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product removed from cart successfully',
                'items' => [],
                'total' => 0
            ]);
    }

    public function test_cart_calculates_total_correctly(): void
    {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 10.50,
            'stock_quantity' => 20
        ]);
        $product2 = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 15.75,
            'stock_quantity' => 20
        ]);

        // Add first product
        $this->postJson('/cart/add', [
            'product_id' => $product1->id,
            'quantity' => 2
        ]);

        // Add second product
        $this->postJson('/cart/add', [
            'product_id' => $product2->id,
            'quantity' => 1
        ]);

        // Check cart
        $response = $this->getJson('/cart');

        $expectedTotal = (10.50 * 2) + (15.75 * 1); // 36.75

        $response->assertStatus(200)
            ->assertJson([
                'total' => $expectedTotal
            ]);
    }

    public function test_cart_displays_all_items_with_details(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Rose Bouquet',
            'price' => 25.00,
            'stock_quantity' => 10
        ]);

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 3
        ]);

        $response = $this->getJson('/cart');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'items' => [
                    '*' => [
                        'product_id',
                        'product' => ['id', 'name', 'price'],
                        'quantity',
                        'price',
                        'subtotal'
                    ]
                ],
                'total'
            ])
            ->assertJsonFragment([
                'quantity' => 3,
                'subtotal' => 75.00
            ]);
    }
}
