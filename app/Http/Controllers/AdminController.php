<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\RevenueService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected RevenueService $revenueService;

    public function __construct(RevenueService $revenueService)
    {
        $this->revenueService = $revenueService;
    }

    /**
     * Display admin dashboard with statistics
     * 
     * GET /admin/dashboard
     * Validates: Requirements 12.6
     */
    public function dashboard()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_categories' => Category::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
        ];

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }

    /**
     * Display product management page
     * 
     * GET /admin/products
     * Validates: Requirements 2.1, 2.2, 2.3, 12.6
     */
    public function products()
    {
        $products = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show create product form
     * 
     * GET /admin/products/create
     */
    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Show edit product form
     * 
     * GET /admin/products/{id}/edit
     */
    public function editProduct($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Display order management page
     * 
     * GET /admin/orders
     * Validates: Requirements 7.1, 7.2, 12.6
     */
    public function orders()
    {
        $orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display order details
     * 
     * GET /admin/orders/{id}
     * Validates: Requirements 7.5, 12.6
     */
    public function showOrder($id)
    {
        $order = Order::with(['user', 'orderItems.product'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Display category management page
     * 
     * GET /admin/categories
     * Validates: Requirements 9.1, 9.2, 9.3, 12.6
     */
    public function categories()
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Display revenue reports page
     * 
     * GET /admin/revenue
     * Validates: Requirements 8.1, 8.3, 12.6
     */
    public function revenue()
    {
        $revenueData = $this->revenueService->getMonthlyRevenue(12);

        return view('admin.revenue', compact('revenueData'));
    }
}
