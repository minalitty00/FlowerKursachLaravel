<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        Storage::fake('public');
    }

    /** @test */
    public function it_can_list_products_with_category()
    {
        $category = Category::factory()->create(['name' => 'Roses']);
        Product::factory()->count(3)->create([
            'category_id' => $category->id,
            'stock_quantity' => 10,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'products' => [
                    '*' => [
                        'id', 'name', 'description', 'price', 'image_url',
                        'category' => ['id', 'name', 'slug'],
                        'stock_quantity', 'created_at', 'updated_at'
                    ]
                ]
            ]);

        $this->assertCount(3, $response->json('products'));
    }

    /** @test */
    public function it_filters_products_by_category()
    {
        $category1 = Category::factory()->create(['name' => 'Roses']);
        $category2 = Category::factory()->create(['name' => 'Tulips']);
        
        Product::factory()->count(2)->create(['category_id' => $category1->id, 'stock_quantity' => 10]);
        Product::factory()->count(3)->create(['category_id' => $category2->id, 'stock_quantity' => 10]);

        $response = $this->getJson("/api/products?category_id={$category1->id}");

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('products'));
    }

    /** @test */
    public function it_searches_products_by_name()
    {
        $category = Category::factory()->create();
        Product::factory()->create(['name' => 'Red Roses', 'category_id' => $category->id, 'stock_quantity' => 10]);
        Product::factory()->create(['name' => 'White Tulips', 'category_id' => $category->id, 'stock_quantity' => 10]);
        Product::factory()->create(['name' => 'Pink Roses', 'category_id' => $category->id, 'stock_quantity' => 10]);

        $response = $this->getJson('/api/products?search=Roses');

        $response->assertStatus(200);
        $products = $response->json('products');
        $this->assertCount(2, $products);
        
        foreach ($products as $product) {
            $this->assertStringContainsString('Roses', $product['name']);
        }
    }

    /** @test */
    public function it_filters_in_stock_products_by_default()
    {
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id, 'stock_quantity' => 10]);
        Product::factory()->count(3)->create(['category_id' => $category->id, 'stock_quantity' => 0]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('products'));
    }

    /** @test */
    public function it_returns_products_sorted_by_creation_date_descending()
    {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create(['name' => 'First', 'category_id' => $category->id, 'stock_quantity' => 10, 'created_at' => now()->subDays(2)]);
        $product2 = Product::factory()->create(['name' => 'Second', 'category_id' => $category->id, 'stock_quantity' => 10, 'created_at' => now()->subDays(1)]);
        $product3 = Product::factory()->create(['name' => 'Third', 'category_id' => $category->id, 'stock_quantity' => 10, 'created_at' => now()]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $products = $response->json('products');
        
        $this->assertEquals('Third', $products[0]['name']);
        $this->assertEquals('Second', $products[1]['name']);
        $this->assertEquals('First', $products[2]['name']);
    }

    /** @test */
    public function it_can_show_single_product()
    {
        $category = Category::factory()->create(['name' => 'Roses']);
        $product = Product::factory()->create([
            'name' => 'Red Rose',
            'category_id' => $category->id,
            'stock_quantity' => 10,
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'product' => [
                    'id' => $product->id,
                    'name' => 'Red Rose',
                    'category' => [
                        'id' => $category->id,
                        'name' => 'Roses',
                    ],
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_for_non_existent_product()
    {
        $response = $this->getJson('/api/products/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function admin_can_create_product_without_image()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->postJson('/api/products', [
            'name' => 'Red Rose',
            'description' => 'Beautiful red rose',
            'price' => 25.99,
            'category_id' => $category->id,
            'stock_quantity' => 50,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Product created successfully',
                'product' => [
                    'name' => 'Red Rose',
                    'price' => '25.99',
                    'stock_quantity' => 50,
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Red Rose',
            'price' => 25.99,
            'stock_quantity' => 50,
        ]);
    }

    /** @test */
    public function admin_can_create_product_with_image()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $image = UploadedFile::fake()->create('rose.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($admin)->postJson('/api/products', [
            'name' => 'Red Rose',
            'description' => 'Beautiful red rose',
            'price' => 25.99,
            'category_id' => $category->id,
            'stock_quantity' => 50,
            'image' => $image,
        ]);

        $response->assertStatus(201);
        
        $product = Product::where('name', 'Red Rose')->first();
        $this->assertNotNull($product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
    }

    /** @test */
    public function non_admin_cannot_create_product()
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/products', [
            'name' => 'Red Rose',
            'description' => 'Beautiful red rose',
            'price' => 25.99,
            'category_id' => $category->id,
            'stock_quantity' => 50,
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function product_creation_validates_required_fields()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->postJson('/api/products', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'description', 'price', 'category_id', 'stock_quantity']);
    }

    /** @test */
    public function product_creation_validates_price_is_positive()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->postJson('/api/products', [
            'name' => 'Red Rose',
            'description' => 'Beautiful red rose',
            'price' => 0,
            'category_id' => $category->id,
            'stock_quantity' => 50,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['price']);
    }

    /** @test */
    public function product_creation_validates_stock_quantity_is_non_negative()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->postJson('/api/products', [
            'name' => 'Red Rose',
            'description' => 'Beautiful red rose',
            'price' => 25.99,
            'category_id' => $category->id,
            'stock_quantity' => -1,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['stock_quantity']);
    }

    /** @test */
    public function admin_can_update_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Old Name',
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($admin)->putJson("/api/products/{$product->id}", [
            'name' => 'New Name',
            'price' => 30.00,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product updated successfully',
                'product' => [
                    'name' => 'New Name',
                    'price' => '30.00',
                ]
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Name',
            'price' => 30.00,
        ]);
    }

    /** @test */
    public function admin_can_update_product_image()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        
        $oldImage = UploadedFile::fake()->create('old.jpg', 100, 'image/jpeg');
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'image_path' => $oldImage->store('products', 'public'),
        ]);
        
        $newImage = UploadedFile::fake()->create('new.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($admin)->putJson("/api/products/{$product->id}", [
            'image' => $newImage,
        ]);

        $response->assertStatus(200);
        
        $product->refresh();
        $this->assertNotNull($product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
    }

    /** @test */
    public function non_admin_cannot_update_product()
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)->putJson("/api/products/{$product->id}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_delete_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($admin)->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product deleted successfully',
            ]);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    /** @test */
    public function admin_can_delete_product_with_image()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        
        $image = UploadedFile::fake()->create('rose.jpg', 100, 'image/jpeg');
        $imagePath = $image->store('products', 'public');
        
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'image_path' => $imagePath,
        ]);

        Storage::disk('public')->assertExists($imagePath);

        $response = $this->actingAs($admin)->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200);
        Storage::disk('public')->assertMissing($imagePath);
    }

    /** @test */
    public function non_admin_cannot_delete_product()
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($user)->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(403);
    }
}
