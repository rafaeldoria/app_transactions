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
}
