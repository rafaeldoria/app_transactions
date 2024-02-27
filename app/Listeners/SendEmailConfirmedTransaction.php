<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\Emails\EmailService;
use App\Events\ConfirmedTransactionEvent;

class SendEmailConfirmedTransaction
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ConfirmedTransactionEvent $event): void
    {
        $transaction = $event->transaction;
        $content = [
            'subject' => 'Confirmed Transaction',
            'body' => 'Your transaction is confirmed.'
        ];
        $user = new User();
        $payer = $user->find($transaction['payer_id']);
        (new EmailService($content))->build($payer->email);
        // Mail::to($payer->email)->send(new SendEmail($content));

        $payee = $user->find($transaction['payee_id']);
        (new EmailService($content))->build($payee->email);
        // Mail::to($payee->email)->send(new SendEmail($content));
    }
}
