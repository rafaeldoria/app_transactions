<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Transactions\TransactionAuthorizer;

class TransactionServiceTest extends TestCase
{
    /**
     * A test authorization transaction service.
     */
    public function test_service_to_authorize_transaction(): void
    {
        $response = (new TransactionAuthorizer)->transactionAuthorizer();
        $this->assertSame(true, $response);
    }
}
