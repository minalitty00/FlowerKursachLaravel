<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = new CartService();
        Session::start();
    }

    public function test_add_item_stores_product_in_session(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);
        
        $this->cartService->addItem($product->id, 2);
        
        $items = $this->cartService->getItems();
        $this->assertCount(1, $items);
        $this->assertEquals($product->id, $items[0]['product_id']);
        $this->assertEquals(2, $items[0]['quantity']);
    }

    public function test_add_item_increments_quantity_for_existing_product(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);
        
        $this->cartService->addItem($product->id, 2);
        $this->cartService->addItem($product->id, 3);
        
        $items = $this->cartService->getItems();
        $this->assertCount(1, $items);
        $this->assertEquals(5, $items[0]['quantity']);
    }

    public function test_update_quantity_changes_item_quantity(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);
        $this->cartService->addItem($product->id, 2);
        
        $this->cartService->updateQuantity($product->id, 5);
        
        $items = $this->cartService->getItems();
        $this->assertEquals(5, $items[0]['quantity']);
    }

    public function test_update_quantity_throws_exception_when_insufficient_stock(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);
        $this->cartService->addItem($product->id, 2);
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient stock');
        
        $this->cartService->updateQuantity($product->id, 10);
    }

    public function test_remove_item_deletes_product_from_cart(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);
        $this->cartService->addItem($product->id, 2);
        
        $this->cartService->removeItem($product->id);
        
        $items = $this->cartService->getItems();
        $this->assertCount(0, $items);
    }

    public function test_get_items_returns_products_with_details(): void
    {
        $product1 = Product::factory()->create(['price' => 10.50, 'stock_quantity' => 10]);
        $product2 = Product::factory()->create(['price' => 15.75, 'stock_quantity' => 10]);
        
        $this->cartService->addItem($product1->id, 2);
        $this->cartService->addItem($product2->id, 1);
        
        $items = $this->cartService->getItems();
        
        $this->assertCount(2, $items);
        $this->assertEquals(21.00, $items[0]['subtotal']);
        $this->assertEquals(15.75, $items[1]['subtotal']);
    }

    public function test_get_total_calculates_correct_sum(): void
    {
        $product1 = Product::factory()->create(['price' => 10.50, 'stock_quantity' => 10]);
        $product2 = Product::factory()->create(['price' => 15.75, 'stock_quantity' => 10]);
        
        $this->cartService->addItem($product1->id, 2); // 21.00
        $this->cartService->addItem($product2->id, 1); // 15.75
        
        $total = $this->cartService->getTotal();
        
        $this->assertEquals(36.75, $total);
    }

    public function test_clear_removes_all_items_from_cart(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);
        $this->cartService->addItem($product->id, 2);
        
        $this->cartService->clear();
        
        $items = $this->cartService->getItems();
        $this->assertCount(0, $items);
    }

    public function test_get_items_returns_empty_array_for_empty_cart(): void
    {
        $items = $this->cartService->getItems();
        
        $this->assertIsArray($items);
        $this->assertCount(0, $items);
    }

    public function test_get_total_returns_zero_for_empty_cart(): void
    {
        $total = $this->cartService->getTotal();
        
        $this->assertEquals(0, $total);
    }
}
