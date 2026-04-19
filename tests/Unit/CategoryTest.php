<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that products relationship is defined.
     */
    public function test_products_relationship_is_defined(): void
    {
        $category = new Category(['name' => 'Test Category', 'slug' => 'test-category']);
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $category->products()
        );
    }

    /**
     * Test that product_count accessor returns zero for category without products.
     */
    public function test_product_count_returns_zero_for_empty_category(): void
    {
        $category = Category::create([
            'name' => 'Empty Category',
            'slug' => 'empty-category'
        ]);

        $this->assertEquals(0, $category->product_count);
    }
}
