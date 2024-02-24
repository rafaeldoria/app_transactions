<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendEmail;
use App\Http\Controllers\Controller;
use App\Services\EmailService;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function confirmedTransaction(User $user)
    {
        $content = [
            'subject' => 'Confirmed Transaction',
            'body' => 'Your transaction is confirmed.'
        ];
        
        $response = (new EmailService($content))->build($user->email);
        return response()->json(['Sended email' => $response['message']]);
    }
}
