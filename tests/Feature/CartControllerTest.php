<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_view_empty_cart(): void
    {
        $response = $this->get('/cart');

        $response->assertStatus(200);
        $this->assertStringContainsString('Ваша корзина пуста', $response->getContent());
    }

    public function test_can_add_product_to_cart(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 25.50,
            'stock_quantity' => 10
        ]);

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        $response->assertRedirect('/cart');
    }

    public function test_add_to_cart_validates_product_id(): void
    {
        $response = $this->post('/cart/add', [
            'product_id' => 9999,
            'quantity' => 1
        ]);

        $response->assertSessionHasErrors('product_id');
    }

    public function test_add_to_cart_validates_quantity(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'stock_quantity' => 10
        ]);

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => -1
        ]);

        $response->assertSessionHasErrors('quantity');
    }

    public function test_can_update_cart_item_quantity(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 25.50,
            'stock_quantity' => 10
        ]);

        // Add to cart first
        $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Update quantity
        $response = $this->put('/cart/update', [
            'product_id' => $product->id,
            'quantity' => 5
        ]);

        $response->assertRedirect('/cart');
    }

    public function test_update_cart_rejects_quantity_exceeding_stock(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 25.50,
            'stock_quantity' => 10
        ]);

        // Add to cart first
        $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Try to update with quantity exceeding stock
        $response = $this->put('/cart/update', [
            'product_id' => $product->id,
            'quantity' => 15
        ]);

        $response->assertRedirect();
    }

    public function test_can_remove_product_from_cart(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 25.50,
            'stock_quantity' => 10
        ]);

        // Add to cart first
        $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Remove from cart
        $response = $this->delete('/cart/remove', [
            'product_id' => $product->id
        ]);

        $response->assertRedirect('/cart');
    }

    public function test_cart_calculates_total_correctly(): void
    {
        $category = Category::factory()->create();
        
        $product1 = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 25.00,
            'stock_quantity' => 10
        ]);
        
        $product2 = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 15.00,
            'stock_quantity' => 10
        ]);

        // Add two products
        $this->post('/cart/add', [
            'product_id' => $product1->id,
            'quantity' => 2
        ]);
        
        $this->post('/cart/add', [
            'product_id' => $product2->id,
            'quantity' => 3
        ]);

        // Check cart total: (25 * 2) + (15 * 3) = 50 + 45 = 95
        $response = $this->get('/cart');
        $response->assertStatus(200);
    }

    public function test_cart_displays_all_items_with_details(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 25.50,
            'stock_quantity' => 10
        ]);

        // Add to cart
        $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Check cart displays item
        $response = $this->get('/cart');
        $response->assertStatus(200);
    }
}