<?php

namespace App\Services\Wallets;

use App\Repositories\WalletRepository;

class WalletService
{
    public function index()
    {
        return (new WalletRepository)->index();
    }

    public function show($id)
    {
        return (new WalletRepository)->show($id);
    }

    public function store(array $data)
    {
        $wallet = (new WalletRepository)->store($data);
        return $wallet;
    }

    public function update(int $id, $data)
    {
        $wallet = $this->show($id);
        (new WalletRepository)->update($wallet, $data);
        return $wallet;
    }

    public function getWalletByUser(int $user_id)
    {
        return (new WalletRepository)->getWalletByUser($user_id);
    }
}
