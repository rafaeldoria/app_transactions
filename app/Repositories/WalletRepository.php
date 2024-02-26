<?php

namespace App\Repositories;

use App\Models\Wallet;
use App\Repositories\Interfaces\IWalletRepository;

class WalletRepository extends BaseRepository implements IWalletRepository
{
    public function index()
    {
        return Wallet::all();
    }

    public function show(Int $id)
    {
        $wallet = Wallet::find($id);
        if (!$wallet) {
            throw new \Exception('Not found', -404);
        }
        return $wallet;
    }

    public function update($wallet, array $data)
    {
        $wallet->update($data);
    }

    public function getWalletByUser(int $user_id)
    {
        return Wallet::where('user_id', $user_id)
            ->whereNull('deleted_at')
            ->first();
    }
}
