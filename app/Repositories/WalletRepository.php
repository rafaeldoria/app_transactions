<?php

namespace App\Repositories;

use Exception;
use App\Models\Wallet;
use App\Repositories\Interfaces\IWalletRepository;

class WalletRepository extends BaseRepository implements IWalletRepository
{
    public function index()
    {
        $wallet = new Wallet();
        return $wallet->all();
    }

    public function show(Int $walletId)
    {
        $wallet = new Wallet();
        $wallet = $wallet->find($walletId);
        if (!$wallet) {
            throw new Exception('Not found', -404);
        }
        return $wallet;
    }

    public function update($wallet, array $data)
    {
        $wallet->update($data);
    }

    public function getWalletByUser(int $userId)
    {
        $wallet = new Wallet();
        return $wallet->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->first();
    }
}
