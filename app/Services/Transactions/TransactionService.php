<?php

namespace App\Services\Transactions;

use App\Repositories\TransactionRepository;

class TransactionService
{
    public function index()
    {
        return (new TransactionRepository)->index();
    }

    public function show($transactionId)
    {
        return (new TransactionRepository)->show($transactionId);
    }

    public function update(int $transactionId, $data)
    {
        $transaction = $this->show($transactionId);
        (new TransactionRepository)->update($transaction, $data);
        return $transaction;
    }

    public function getTransacationByUser(int $userId)
    {
        return (new TransactionRepository)->getTransacationByUser($userId);
    }
}
