<?php

namespace App\Http\Controllers;

use App\Services\RevenueService;
use Illuminate\Http\JsonResponse;

class RevenueController extends Controller
{
    protected RevenueService $revenueService;

    public function __construct(RevenueService $revenueService)
    {
        $this->revenueService = $revenueService;
    }

    /**
     * Get monthly revenue report.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $revenue = $this->revenueService->getMonthlyRevenue();

        return response()->json([
            'data' => $revenue
        ]);
    }
}
