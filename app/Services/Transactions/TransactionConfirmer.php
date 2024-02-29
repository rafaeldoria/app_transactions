<?php

namespace App\Services\Transactions;

use Exception;
use App\Models\User;
use App\Models\Transaction;

class TransactionConfirmer
{
    public function transactionConfirmer(Transaction $transaction)
    {
        try {
            $user = new User();
            $payer = $user->findOrFail($transaction->payer_id);
            $payer->wallet()->update([
                'amount' => $payer->wallet->amount - $transaction->amount
            ]);
            
            $payee = $user->findOrFail($transaction->payee_id);
            $payee->wallet()->update([
                'amount' => $payee->wallet->amount + $transaction->amount
            ]);

            $transaction->confirmed = 1;
            $transaction->update($transaction->toArray());
            return $transaction;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}