<?php

namespace App\Services\Transactions;

use Exception;
use App\Models\User;
use App\Models\Transaction;
use App\Jobs\TransactionJob;
use App\Models\Document;
use Illuminate\Http\Request;

class CreateTransactionService
{
    public function createTransaction(Request $request)
    {
        try {
            $this->validateTrasancation($request);
            $transaction = (new Transaction)->create($request->all());
            TransactionJob::dispatch($transaction)
                ->delay(now()->addSeconds(10))
                ->onQueue('transactions');
            return $transaction;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function validateTrasancation(Request $request)
    {
        $user = new User();
        $payer = $user->find($request->payer_id);
        $payee = $user->find($request->payee_id);

        if (!$payer || !$payee) {
            throw new Exception('Not found payer and/or payee.', -404);
        }

        if(empty($payer->document[0]) || empty($payee->document[0])){
            throw new Exception('necessary that the two user have a document.');
        }

        if($payer->type == User::__SHOPMAN__){
            throw new Exception('you dont have permission to effect this transaction.');
        }
        
        if($payer->wallet->amount <= $request->amount){
            throw new Exception('insufficient funds to payer.');
        }
    }
    
}