<?php

namespace App\Repositories;

use Exception;
use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{
    public function index()
    {
        $transaction = new Transaction();
        return $transaction->all();
    }

    public function show(Int $transactionId)
    {
        $transaction = new Transaction();
        $transaction = $transaction->find($transactionId);
        if (!$transaction) {
            throw new Exception('Not found', -404);
        }
        return $transaction;
    }

    public function update($transaction, array $data)
    {
        $transaction->update($data);
    }

    public function getTransacationByUser(int $userId)
    {
        $transactions = new Transaction();
        $transactions = $transactions->where('payer_id', $userId)
            ->orWhere('payee_id', $userId)
            ->whereNull('deleted_at')
            ->get();
            
        if (!$transactions) {
            throw new Exception('Not found', -404);
        }
        return $transactions;
    }
}
