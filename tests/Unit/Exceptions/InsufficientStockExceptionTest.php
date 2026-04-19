<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\InsufficientStockException;
use Tests\TestCase;

class InsufficientStockExceptionTest extends TestCase
{
    public function test_exception_has_correct_message(): void
    {
        $exception = new InsufficientStockException('Rose Bouquet', 10, 5);
        
        $this->assertEquals(
            "Insufficient stock for product 'Rose Bouquet'. Requested: 10, Available: 5",
            $exception->getMessage()
        );
    }

    public function test_render_returns_json_response_with_400_status(): void
    {
        $exception = new InsufficientStockException('Rose Bouquet', 10, 5);
        
        $response = $exception->render();
        
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals([
            'error' => 'Insufficient stock',
            'message' => "Insufficient stock for product 'Rose Bouquet'. Requested: 10, Available: 5",
            'product' => 'Rose Bouquet',
            'requested' => 10,
            'available' => 5,
        ], $response->getData(true));
    }
}
