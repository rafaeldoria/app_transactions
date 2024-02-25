<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\TransactionService;

class TransactionServiceTest extends TestCase
{
    /**
     * A test authorization transaction service.
     */
    public function test_service_to_authorize_transaction(): void
    {
        $transactionService = new TransactionService();
        
        $response = $transactionService->toAuthorizeTransaction();
        $this->assertSame(true, $response);
    }
}
