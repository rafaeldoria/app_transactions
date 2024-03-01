<?php

namespace App\Services\Wallets;

use App\Models\Wallet;
use App\Services\Redis\RedisService;
use Illuminate\Support\Facades\Redis;
use App\Repositories\WalletRepository;

class WalletService
{
    protected $redis;

    public function __construct()
    {
        $this->redis = new RedisService();
    }

    public function index()
    {
        $key = 'wallet.service.repository.index';
        if(!$this->redis->exists($key) || app()->environment() === 'testing'){
            
            $wallet = (new WalletRepository)->index();
            $this->redis->set($key, $wallet, 30);
            return $wallet;
        }
        $dataArray = json_decode($this->redis->get($key), true);
        return collect($dataArray);
    }

    public function show($walletId)
    {
        $key = 'wallet.service.repository.show'.$walletId;
        if(!$this->redis->exists($key) || app()->environment() === 'testing'){
            $wallet = (new WalletRepository)->show($walletId);

            if(!app()->environment() === 'testing'){
                $this->redis->set($key, $wallet, 30);
            }

            return $wallet;
        }
        $dataArray = json_decode($this->redis->get($key), true);
        return collect($dataArray);
    }

    public function store(array $data)
    {
        $wallet = (new WalletRepository)->store($data);
        return $wallet;
    }

    public function update(int $walletId, $data)
    {
        $wallet = $this->show($walletId);
        if(!$wallet instanceof Wallet){
            $dataArray = $wallet;
            if(!is_array($wallet)){
                $dataArray = json_decode($wallet, true);
            }
            $wallet = new Wallet();
            $wallet->fill($dataArray);
            $wallet->id = $dataArray['id'];
        }
        (new WalletRepository)->update($wallet, $data);
        return $wallet;
    }

    public function getWalletByUser(int $userId)
    {
        $key = 'wallet.service.repository.getbyuser.' . $userId;
        if(!$this->redis->exists($key) || app()->environment() === 'testing'){
            $wallet = (new WalletRepository)->getWalletByUser($userId);

            if(!app()->environment() === 'testing'){
                $this->redis->set($key, $wallet, 30);
            }

            return $wallet;
        }
        $dataArray = json_decode($this->redis->get($key), true);
        return collect($dataArray);
    }
}
