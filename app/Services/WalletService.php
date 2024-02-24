<?php 

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;

class WalletService
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