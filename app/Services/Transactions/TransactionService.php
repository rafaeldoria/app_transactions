<?php

namespace App\Services\Transactions;

use App\Repositories\TransactionRepository;

class TransactionService
{
    public function index()
    {
        return (new TransactionRepository)->index();
    }

    public function show($id)
    {
        return (new TransactionRepository)->show($id);
    }

    public function update(int $id, $data)
    {
        $transaction = $this->show($id);
        (new TransactionRepository)->update($transaction, $data);
        return $transaction;
    }
}
