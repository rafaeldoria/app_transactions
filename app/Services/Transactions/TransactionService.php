<?php

namespace App\Services\Transactions;

use App\Models\Transaction;
use App\Services\Redis\RedisService;
use Illuminate\Support\Facades\Redis;
use App\Repositories\TransactionRepository;

class TransactionService
{
    protected $redis;

    public function __construct()
    {
        $this->redis = new RedisService();
    }
    
    public function index()
    {
        $key = 'transaction.service.repository.index';
        if(!$this->redis->exists($key) || app()->environment() === 'testing'){
            
            $transaction = (new TransactionRepository)->index();
            if(!app()->environment() === 'testing'){
                $this->redis->set($key, $transaction, 20);
            }
            return $transaction;
        }
        $dataArray = json_decode($this->redis->get($key), true);
        return collect($dataArray);
    }

    public function show($transactionId)
    {
        return (new TransactionRepository)->show($transactionId);
        $key = 'transaction.service.repository.show.' . $transactionId;
        if(!$this->redis->exists($key) || app()->environment() === 'testing'){
            $transaction = (new TransactionRepository)->show($transactionId);
            if(!app()->environment() === 'testing'){
                $this->redis->set($key, $transaction, 30);
            }

            return $transaction;
        }
        $dataArray = json_decode($this->redis->get($key), true);
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
        if(!$this->redis->exists($key) || app()->environment() === 'testing'){
            $transaction = (new TransactionRepository)->getTransacationByUser($userId);

            if(!app()->environment() === 'testing'){
                $this->redis->set($key, $transaction, 30);
            }

            return $transaction;
        }
        $dataArray = json_decode($this->redis->get($key), true);
        return collect($dataArray);
    }
}
