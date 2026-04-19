<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /** @test */
    public function it_can_list_categories_with_product_count()
    {
        $category1 = Category::factory()->create(['name' => 'Roses']);
        $category2 = Category::factory()->create(['name' => 'Tulips']);
        
        Product::factory()->count(3)->create(['category_id' => $category1->id]);
        Product::factory()->count(2)->create(['category_id' => $category2->id]);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'categories' => [
                    '*' => ['id', 'name', 'slug', 'product_count', 'created_at', 'updated_at']
                ]
            ]);

        $categories = $response->json('categories');
        $this->assertCount(2, $categories);
        
        $roses = collect($categories)->firstWhere('name', 'Roses');
        $this->assertEquals(3, $roses['product_count']);
        
        $tulips = collect($categories)->firstWhere('name', 'Tulips');
        $this->assertEquals(2, $tulips['product_count']);
    }

    /** @test */
    public function admin_can_create_category()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->postJson('/api/categories', [
            'name' => 'Orchids',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Category created successfully',
                'category' => [
                    'name' => 'Orchids',
                    'slug' => 'orchids',
                ]
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Orchids',
            'slug' => 'orchids',
        ]);
    }

    /** @test */
    public function non_admin_cannot_create_category()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->postJson('/api/categories', [
            'name' => 'Orchids',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function category_name_must_be_unique()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Category::factory()->create(['name' => 'Roses']);

        $response = $this->actingAs($admin)->postJson('/api/categories', [
            'name' => 'Roses',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function admin_can_update_category()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($admin)->putJson("/api/categories/{$category->id}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Category updated successfully',
                'category' => [
                    'name' => 'New Name',
                    'slug' => 'new-name',
                ]
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'New Name',
            'slug' => 'new-name',
        ]);
    }

    /** @test */
    public function non_admin_cannot_update_category()
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user)->putJson("/api/categories/{$category->id}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_delete_category_without_products()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Category deleted successfully',
            ]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    /** @test */
    public function admin_cannot_delete_category_with_products()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($admin)->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Cannot delete category with existing products',
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }

    /** @test */
    public function non_admin_cannot_delete_category()
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(403);
    }
}
