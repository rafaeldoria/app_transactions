<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionService
{
    // protected $property;

    // public function __construct($property)
    // {
    //     $this->property = $property;
    // }

    public function createTransaction(Request $request)
    {
        try {
            $payer = User::findOrFail($request->payer_id);
            $payee = User::findOrFail($request->payee_id);

            if($payer->type == User::__SHOPMAN__){
                throw new Exception('you dont have permission to effect this transaction.');
            }
            
            if($payer->wallet->amount <= 0){
                throw new Exception('insufficient funds to payer.');
            }
            dd($payee->wallet->amount);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}