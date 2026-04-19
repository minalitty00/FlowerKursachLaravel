<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\EmptyCartException;
use Tests\TestCase;

class EmptyCartExceptionTest extends TestCase
{
    public function test_exception_has_correct_message(): void
    {
        $exception = new EmptyCartException();
        
        $this->assertEquals('Cannot create order from empty cart', $exception->getMessage());
    }

    public function test_render_returns_json_response_with_400_status(): void
    {
        $exception = new EmptyCartException();
        
        $response = $exception->render();
        
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals([
            'error' => 'Empty cart',
            'message' => 'Cannot create order from empty cart',
        ], $response->getData(true));
    }
}
