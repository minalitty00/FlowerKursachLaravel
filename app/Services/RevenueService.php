<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class RevenueService
{
    /**
     * Get monthly revenue for the last 12 months.
     *
     * @param int $months Number of months to retrieve (default: 12)
     * @return array Array of monthly revenue data
     */
    public function getMonthlyRevenue(int $months = 12): array
    {
        $startDate = now()->subMonths($months)->startOfMonth();

        $revenue = Order::completed()
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'total' => number_format((float) $item->total, 2, '.', '')
                ];
            })
            ->toArray();

        return $revenue;
    }

    /**
     * Calculate total revenue for a specific period.
     *
     * @param string $startDate Start date in Y-m-d format
     * @param string $endDate End date in Y-m-d format
     * @return string Total revenue formatted with 2 decimal places
     */
    public function calculateRevenue(string $startDate, string $endDate): string
    {
        $total = Order::completed()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        return number_format((float) $total, 2, '.', '');
    }
}
