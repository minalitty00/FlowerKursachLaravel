<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_item_has_correct_fillable_fields(): void
    {
        $orderItem = new OrderItem();
        
        $expectedFillable = [
            'order_id',
            'product_id',
            'product_name',
            'quantity',
            'price',
            'subtotal',
        ];
        
        $this->assertEquals($expectedFillable, $orderItem->getFillable());
    }

    public function test_order_item_has_correct_casts(): void
    {
        $orderItem = new OrderItem();
        $casts = $orderItem->getCasts();
        
        $this->assertEquals('integer', $casts['quantity']);
        $this->assertEquals('decimal:2', $casts['price']);
        $this->assertEquals('decimal:2', $casts['subtotal']);
    }

    public function test_order_item_belongs_to_order(): void
    {
        $orderItem = new OrderItem();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $orderItem->order()
        );
    }

    public function test_order_item_belongs_to_product(): void
    {
        $orderItem = new OrderItem();
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $orderItem->product()
        );
    }
}
