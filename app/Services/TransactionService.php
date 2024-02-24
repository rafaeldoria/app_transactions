<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionService
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function createTransaction(Request $request)
    {
        try {
            $payer = User::findOrFail($request->payer_id);

            if($payer->type == User::__SHOPMAN__){
                throw new Exception('you dont have permission to effect this transaction.');
            }
            
            if($payer->wallet->amount <= $request->amount){
                throw new Exception('insufficient funds to payer.');
            }
            return $this->transaction->create($request->all());
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function toConfirmTransaction(Transaction $transaction)
    {
        try {
            $payer = User::findOrFail($transaction->payer_id);
            $payer->wallet()->update([
                'amount' => $payer->wallet->amount - $transaction->amount
            ]);
            
            $payee = User::findOrFail($transaction->payee_id);
            $payee->wallet()->update([
                'amount' => $payee->wallet->amount + $transaction->amount
            ]);

            $transaction->confirmed = 1;
            $transaction->update($transaction->toArray());
            return $transaction;
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    
}