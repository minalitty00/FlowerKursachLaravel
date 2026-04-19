<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display the cart contents
     * 
     * GET /cart
     */
    public function index()
    {
        $items = $this->cartService->getItems();
        $total = $this->cartService->getTotal();

        return view('cart.index', [
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Add a product to the cart
     * 
     * POST /cart/add
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $this->cartService->addItem($validated['product_id'], $validated['quantity']);

            return redirect()->route('cart.index')
                ->with('success', 'Product added to cart successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add product to cart: ' . $e->getMessage());
        }
    }

    /**
     * Update product quantity in cart
     * 
     * PUT /cart/update
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $this->cartService->updateQuantity($validated['product_id'], $validated['quantity']);

            return redirect()->route('cart.index')
                ->with('success', 'Cart updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update cart: ' . $e->getMessage());
        }
    }

    /**
     * Remove a product from the cart
     * 
     * DELETE /cart/remove
     */
    public function remove(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $this->cartService->removeItem($validated['product_id']);

        return redirect()->route('cart.index')
            ->with('success', 'Product removed from cart successfully');
    }
}
