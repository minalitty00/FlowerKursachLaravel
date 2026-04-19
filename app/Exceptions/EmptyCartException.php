<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class EmptyCartException extends Exception
{
    public function __construct()
    {
        parent::__construct('Cannot create order from empty cart');
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Empty cart',
            'message' => $this->getMessage(),
        ], 400);
    }
}
