<?php 

namespace App\Services\Wallets;

use App\Models\User;
use App\Models\Wallet;

class CreateWalletService
{
    public function createWallet(User $user)
    {
        $newWallet = [
            'user_id' => $user->id,
            'amount' => 0
        ];
        $wallet = new Wallet();
        return $wallet->create($newWallet);
    }
}