<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RevenueControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_revenue_report(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->getJson('/api/admin/revenue');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => []
            ]);
    }

    public function test_non_admin_cannot_access_revenue_report(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->getJson('/api/admin/revenue');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_revenue_report(): void
    {
        $response = $this->getJson('/api/admin/revenue');

        $response->assertStatus(401);
    }

    public function test_revenue_report_returns_monthly_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        // Create completed orders
        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 100.00,
            'created_at' => now()->subMonth(),
        ]);

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 150.00,
            'created_at' => now()->subMonth(),
        ]);

        $response = $this->actingAs($admin)
            ->getJson('/api/admin/revenue');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['month', 'total']
                ]
            ]);

        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals('250.00', $data[0]['total']);
    }

    public function test_revenue_report_excludes_non_completed_orders(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        // Create completed order
        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 100.00,
            'created_at' => now()->subMonth(),
        ]);

        // Create pending order
        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_amount' => 50.00,
            'created_at' => now()->subMonth(),
        ]);

        $response = $this->actingAs($admin)
            ->getJson('/api/admin/revenue');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals('100.00', $data[0]['total']);
    }

    public function test_revenue_report_formats_amounts_with_two_decimals(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 99.5,
            'created_at' => now()->subMonth(),
        ]);

        $response = $this->actingAs($admin)
            ->getJson('/api/admin/revenue');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals('99.50', $data[0]['total']);
    }
}
