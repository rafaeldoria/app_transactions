<?php

namespace Tests\Unit;

use App\Services\EmailService;
use PHPUnit\Framework\TestCase;

class EmailServiceTest extends TestCase
{
    /**
     * Test return send email service.
     */
    public function test_send_email_true_messagem(): void
    {
        $content = [
            'subject' => 'Confirmed Transaction',
            'body' => 'Your transaction is confirmed.'
        ];
        $email = new EmailService($content);
        $response = $email->build(fake()->unique()->safeEmail());
        $this->assertArrayHasKey('message', $response);
        $this->assertSame(true, $response['message']);
    }
}
