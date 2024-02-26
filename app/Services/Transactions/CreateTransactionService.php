<?php

namespace App\Services\Transactions;

use Exception;
use App\Models\User;
use App\Models\Transaction;
use App\Jobs\TransactionJob;
use Illuminate\Http\Request;

class CreateTransactionService
{
    public function createTransaction(Request $request)
    {
        try {
            $user = new User();
            $payer = $user->findOrFail($request->payer_id);

            if($payer->type == User::__SHOPMAN__){
                throw new Exception('you dont have permission to effect this transaction.');
            }
            
            if($payer->wallet->amount <= $request->amount){
                throw new Exception('insufficient funds to payer.');
            }
            
            $transaction = (new Transaction)->create($request->all());
            TransactionJob::dispatch($transaction)
                ->delay(now()->addSeconds(10))
                ->onQueue('transactions');
            return $transaction;
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    
}