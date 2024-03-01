<?php

namespace App\Services\Transactions;

use App\Models\Transaction;
use Illuminate\Support\Facades\Redis;
use App\Repositories\TransactionRepository;

class TransactionService
{
    public function index()
    {
        $key = 'transaction.service.repository.index';
        if(!Redis::exists($key) || app()->environment() === 'testing'){
            
            $transaction = (new TransactionRepository)->index();
            Redis::set($key, $transaction, 'EX', 20);
            return $transaction;
        }
        $dataArray = json_decode(Redis::get($key), true);
        return collect($dataArray);
    }

    public function show($transactionId)
    {
        return (new TransactionRepository)->show($transactionId);
        $key = 'transaction.service.repository.show.' . $transactionId;
        if(!Redis::exists($key) || app()->environment() === 'testing'){
            $transaction = (new TransactionRepository)->show($transactionId);
            if(!app()->environment() === 'testing'){
                Redis::set($key, $transaction, 'EX', 30);
            }

            return $transaction;
        }
        $dataArray = json_decode(Redis::get($key), true);
        return collect($dataArray);
    }

    public function update(int $transactionId, $data)
    {
        $transaction = $this->show($transactionId);
        if(!$transaction instanceof Transaction){
            $dataArray = $transaction;
            if(!is_array($transaction)){
                $dataArray = json_decode($transaction, true);
            }
            $transaction = new Transaction();
            $transaction->fill($dataArray);
            $transaction->id = $dataArray['id'];
        }
        (new TransactionRepository)->update($transaction, $data);
        return $transaction;
    }

    public function getTransacationByUser(int $userId)
    {
        $key = 'transaction.service.repository.getbyuser.' . $userId;
        if(!Redis::exists($key) || app()->environment() === 'testing'){
            $transaction = (new TransactionRepository)->getTransacationByUser($userId);

            if(!app()->environment() === 'testing'){
                Redis::set($key, $transaction, 'EX', 30);
            }

            return $transaction;
        }
        $dataArray = json_decode(Redis::get($key), true);
        return collect($dataArray);
    }
}
