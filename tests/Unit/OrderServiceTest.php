<?php

namespace Tests\Unit;

use App\Services\OrderService;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    /**
     * A test to return total calculate of the itens.
     */
    public function test_total_calculate(): void
    {
        $orderService = new OrderService();

        $itens = [
            ['amount' => 2, 'price' => 10.00],
            ['amount' => 3, 'price' => 25.00],
            ['amount' => 5, 'price' => 28.00],
        ];

        $expectedTotal = 235.00;
        $this->assertEquals($expectedTotal, $orderService->totalCalculate($itens));

        $this->assertEquals(0, $orderService->totalCalculate([]));

        $itens = [
            ['amount' => 2, 'price' => 10.50],
            ['amount' => 3, 'price' => 25.10],
            ['amount' => 5, 'price' => 18.00],
        ];

        $expectedTotal = 186.3;
        $this->assertEquals($expectedTotal, $orderService->totalCalculate($itens));
    }
}
