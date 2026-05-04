<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\CartService;
use Illuminate\Http\Request;

class OrderWebController extends Controller
{
    protected OrderService $orderService;
    protected CartService $cartService;

    public function __construct(OrderService $orderService, CartService $cartService)
    {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }

    /**
     * Display user order history
     * 
     * GET /orders
     * Validates: Requirements 6.2, 12.2
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to view your orders');
        }

        $orders = Order::with('orderItems.product')
            ->forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
        ]);
    }

    /**
     * Display order details
     * 
     * GET /orders/{id}
     * Validates: Requirements 6.3, 12.2
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to view order details');
        }

        $order = Order::with('orderItems.product')->findOrFail($id);

        // Check authorization: user can only see their own orders
        if ($order->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    /**
     * Display checkout page
     * 
     * GET /checkout
     */
    public function checkout(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to checkout');
        }

        $cartItems = $this->cartService->getItems();
        $total = $this->cartService->getTotal();

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        return view('orders.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'user' => $user,
        ]);
    }

    /**
     * Process checkout and create order
     * 
     * POST /orders/checkout
     */
    public function processCheckout(StoreOrderRequest $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to complete checkout');
        }

        $validated = $request->validated();

        try {
            $order = $this->orderService->createOrder(
                userId: $user->id,
                customerName: $validated['customer_name'],
                customerEmail: $validated['email'],
                customerPhone: $validated['phone'],
                deliveryAddress: $validated['address'],
                deliveryDate: $validated['delivery_date'] ?? null,
                deliveryTime: $validated['delivery_time'] ?? null
            );

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Заказ успешно оформлен! Номер заказа: ' . $order->order_number);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Не удалось создать заказ: ' . $e->getMessage());
        }
    }
}
