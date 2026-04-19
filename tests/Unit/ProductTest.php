<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_casts()
    {
        $product = Product::factory()->create([
            'price' => 99.99,
            'stock_quantity' => 10
        ]);

        $this->assertIsString($product->price);
        $this->assertEquals('99.99', $product->price);
        $this->assertIsInt($product->stock_quantity);
    }

    /** @test */
    public function it_belongs_to_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }

    /** @test */
    public function in_stock_scope_filters_products_with_stock()
    {
        Product::factory()->create(['stock_quantity' => 0]);
        Product::factory()->create(['stock_quantity' => 5]);
        Product::factory()->create(['stock_quantity' => 10]);

        $inStockProducts = Product::inStock()->get();

        $this->assertCount(2, $inStockProducts);
        $this->assertTrue($inStockProducts->every(fn($p) => $p->stock_quantity > 0));
    }

    /** @test */
    public function by_category_scope_filters_products_by_category()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Product::factory()->count(3)->create(['category_id' => $category1->id]);
        Product::factory()->count(2)->create(['category_id' => $category2->id]);

        $category1Products = Product::byCategory($category1->id)->get();
        $category2Products = Product::byCategory($category2->id)->get();

        $this->assertCount(3, $category1Products);
        $this->assertCount(2, $category2Products);
        $this->assertTrue($category1Products->every(fn($p) => $p->category_id === $category1->id));
    }
}
