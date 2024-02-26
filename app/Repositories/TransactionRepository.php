<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{
    public function index()
    {
        return Transaction::all();
    }

    public function show(Int $id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            throw new \Exception('Not found', -404);
        }
        return $transaction;
    }

    public function update($transaction, array $data)
    {
        $transaction->update($data);
    }
}
