<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Models\User;
use App\Services\RevenueService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RevenueServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RevenueService $revenueService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->revenueService = new RevenueService();
    }

    public function test_get_monthly_revenue_returns_completed_orders_only(): void
    {
        $user = User::factory()->create();

        // Create completed order
        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 100.00,
            'created_at' => now()->subMonth(),
        ]);

        // Create pending order (should not be included)
        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_amount' => 50.00,
            'created_at' => now()->subMonth(),
        ]);

        $revenue = $this->revenueService->getMonthlyRevenue();

        $this->assertNotEmpty($revenue);
        $this->assertEquals('100.00', $revenue[0]['total']);
    }

    public function test_get_monthly_revenue_groups_by_month(): void
    {
        $user = User::factory()->create();

        // Create orders in different months
        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 100.00,
            'created_at' => now()->subMonths(2)->startOfMonth(),
        ]);

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 150.00,
            'created_at' => now()->subMonth()->startOfMonth(),
        ]);

        $revenue = $this->revenueService->getMonthlyRevenue();

        $this->assertCount(2, $revenue);
        $this->assertEquals('100.00', $revenue[0]['total']);
        $this->assertEquals('150.00', $revenue[1]['total']);
    }

    public function test_get_monthly_revenue_formats_amounts_with_two_decimals(): void
    {
        $user = User::factory()->create();

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 99.5,
            'created_at' => now()->subMonth(),
        ]);

        $revenue = $this->revenueService->getMonthlyRevenue();

        $this->assertEquals('99.50', $revenue[0]['total']);
    }

    public function test_get_monthly_revenue_returns_last_12_months_by_default(): void
    {
        $user = User::factory()->create();

        // Create order 13 months ago (should not be included)
        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 100.00,
            'created_at' => now()->subMonths(13),
        ]);

        // Create order 11 months ago (should be included)
        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 150.00,
            'created_at' => now()->subMonths(11),
        ]);

        $revenue = $this->revenueService->getMonthlyRevenue();

        $this->assertCount(1, $revenue);
        $this->assertEquals('150.00', $revenue[0]['total']);
    }

    public function test_calculate_revenue_returns_total_for_period(): void
    {
        $user = User::factory()->create();

        $startDate = now()->subDays(10)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 100.00,
            'created_at' => now()->subDays(5),
        ]);

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 50.00,
            'created_at' => now()->subDays(3),
        ]);

        $total = $this->revenueService->calculateRevenue($startDate, $endDate);

        $this->assertEquals('150.00', $total);
    }

    public function test_calculate_revenue_excludes_non_completed_orders(): void
    {
        $user = User::factory()->create();

        $startDate = now()->subDays(10)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 100.00,
            'created_at' => now()->subDays(5),
        ]);

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_amount' => 50.00,
            'created_at' => now()->subDays(3),
        ]);

        $total = $this->revenueService->calculateRevenue($startDate, $endDate);

        $this->assertEquals('100.00', $total);
    }

    public function test_calculate_revenue_formats_with_two_decimals(): void
    {
        $user = User::factory()->create();

        $startDate = now()->subDays(10)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');

        Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'total_amount' => 99.5,
            'created_at' => now()->subDays(5),
        ]);

        $total = $this->revenueService->calculateRevenue($startDate, $endDate);

        $this->assertEquals('99.50', $total);
    }
}
