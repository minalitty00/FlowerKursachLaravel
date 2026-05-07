<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of orders
     * 
     * GET /api/orders
     * - Regular users see only their orders
     * - Admins see all orders
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if ($user && $user->isAdmin()) {
            // Admin sees all orders
            $orders = Order::with('orderItems')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Regular user sees only their orders
            $orders = Order::with('orderItems')
                ->forUser($user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json($orders);
    }

    /**
     * Display the specified order
     * 
     * GET /api/orders/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        
        $order = Order::with('orderItems')->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        // Check authorization: user can only see their own orders unless admin
        if (!$user->isAdmin() && $order->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json($order);
    }

    /**
     * Store a newly created order
     * 
     * POST /api/orders
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        try {
            $order = $this->orderService->createOrder(
                userId: $user->id,
                customerName: $validated['customer_name'],
                customerEmail: $validated['email'],
                customerPhone: $validated['phone'],
                deliveryAddress: $validated['address']
            );

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update the order status
     * 
     * PUT /api/orders/{id}/status
     * Admin only
     */
    public function updateStatus(UpdateOrderStatusRequest $request, int $id)
    {
        $validated = $request->validated();

        try {
            $order = $this->orderService->updateOrderStatus($id, $validated['status']);

            // Return JSON for API requests
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Order status updated successfully',
                    'order' => $order
                ]);
            }

            // Return redirect for web requests
            return redirect()->route('admin.orders.show', $id)
                ->with('success', 'Статус заказа успешно обновлён!');
        } catch (\Exception $e) {
            // Return JSON for API requests
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to update order status',
                    'error' => $e->getMessage()
                ], 422);
            }

            // Return redirect for web requests
            return redirect()->route('admin.orders.show', $id)
                ->with('error', 'Не удалось обновить статус заказа: ' . $e->getMessage());
        }
    }

    /**
     * Delete an order
     * 
     * DELETE /api/orders/{id}
     * Admin only
     */
    public function destroy(Request $request, int $id)
    {
        $user = $request->user();
        
        if (!$user || !$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Admin access required.'
                ], 403);
            }
            abort(403, 'Unauthorized. Admin access required.');
        }

        try {
            $this->orderService->deleteOrder($id);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Order deleted successfully'
                ]);
            }

            return redirect()->route('admin.orders.index')
                ->with('success', 'Заказ успешно удалён!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Order not found'
                ], 404);
            }
            abort(404, 'Order not found');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete order',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.orders.index')
                ->with('error', 'Не удалось удалить заказ: ' . $e->getMessage());
        }
    }
}
