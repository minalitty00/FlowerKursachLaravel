<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InsufficientStockException extends Exception
{
    protected $productName;
    protected $requestedQuantity;
    protected $availableQuantity;

    public function __construct(string $productName, int $requestedQuantity, int $availableQuantity)
    {
        $this->productName = $productName;
        $this->requestedQuantity = $requestedQuantity;
        $this->availableQuantity = $availableQuantity;
        
        parent::__construct(
            "Insufficient stock for product '{$productName}'. Requested: {$requestedQuantity}, Available: {$availableQuantity}"
        );
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Insufficient stock',
            'message' => $this->getMessage(),
            'product' => $this->productName,
            'requested' => $this->requestedQuantity,
            'available' => $this->availableQuantity,
        ], 400);
    }
}
