<?php

namespace App\Services\Wallets;

use App\Models\Wallet;
use Illuminate\Support\Facades\Redis;
use App\Repositories\WalletRepository;

class WalletService
{
    public function index()
    {
        $key = 'wallet.service.repository.index';
        if(!Redis::exists($key) || app()->environment() === 'testing'){
            
            $wallet = (new WalletRepository)->index();
            Redis::set($key, $wallet, 'EX', 30);
            return $wallet;
        }
        $dataArray = json_decode(Redis::get($key), true);
        return collect($dataArray);
    }

    public function show($walletId)
    {
        $key = 'wallet.service.repository.show'.$walletId;
        if(!Redis::exists($key) || app()->environment() === 'testing'){
            $wallet = (new WalletRepository)->show($walletId);

            if(!app()->environment() === 'testing'){
                Redis::set($key, $wallet, 'EX', 30);
            }

            return $wallet;
        }
        $dataArray = json_decode(Redis::get($key), true);
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
        if(!Redis::exists($key) || app()->environment() === 'testing'){
            $wallet = (new WalletRepository)->getWalletByUser($userId);

            if(!app()->environment() === 'testing'){
                Redis::set($key, $wallet, 'EX', 30);
            }

            return $wallet;
        }
        $dataArray = json_decode(Redis::get($key), true);
        return collect($dataArray);
    }
}
