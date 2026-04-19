<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
    }

    /** @test */
    public function admin_can_access_dashboard()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('stats');
        $response->assertViewHas('recentOrders');
    }

    /** @test */
    public function non_admin_cannot_access_dashboard()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_access_products_page()
    {
        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
        $response->assertViewHas('products');
        $response->assertViewHas('categories');
    }

    /** @test */
    public function admin_can_access_create_product_page()
    {
        Category::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.create');
        $response->assertViewHas('categories');
    }

    /** @test */
    public function admin_can_access_edit_product_page()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.edit', $product->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.edit');
        $response->assertViewHas('product');
        $response->assertViewHas('categories');
    }

    /** @test */
    public function admin_can_access_orders_page()
    {
        Order::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.index');
        $response->assertViewHas('orders');
    }

    /** @test */
    public function admin_can_access_order_details_page()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.orders.show', $order->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.show');
        $response->assertViewHas('order');
    }

    /** @test */
    public function admin_can_access_categories_page()
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
        $response->assertViewHas('categories');
    }

    /** @test */
    public function admin_can_access_revenue_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.revenue'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.revenue');
        $response->assertViewHas('revenueData');
    }

    /** @test */
    public function non_admin_cannot_access_admin_pages()
    {
        $routes = [
            'admin.dashboard',
            'admin.products.index',
            'admin.orders.index',
            'admin.categories.index',
            'admin.revenue',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($this->user)
                ->get(route($route));

            $response->assertStatus(403);
        }
    }
}
