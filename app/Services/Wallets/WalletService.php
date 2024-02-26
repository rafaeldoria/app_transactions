<?php

namespace App\Services\Wallets;

use App\Repositories\WalletRepository;

class WalletService
{
    public function index()
    {
        return (new WalletRepository)->index();
    }

    public function show($walletId)
    {
        return (new WalletRepository)->show($walletId);
    }

    public function store(array $data)
    {
        $wallet = (new WalletRepository)->store($data);
        return $wallet;
    }

    public function update(int $walletId, $data)
    {
        $wallet = $this->show($walletId);
        (new WalletRepository)->update($wallet, $data);
        return $wallet;
    }

    public function getWalletByUser(int $userId)
    {
        return (new WalletRepository)->getWalletByUser($userId);
    }
}
