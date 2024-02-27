<?php

namespace App\Services\Users;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Wallets\CreateWalletService;

class UserService
{
    public function index()
    {
        return (new UserRepository)->index();
    }

    public function show($userId)
    {
        return (new UserRepository)->show($userId);
    }

    public function store(array $data)
    {
        $user = (new UserRepository)->store($data);
        (new CreateWalletService)->createWallet($user);
        return $user;
    }

    public function update(int $userId, $data)
    {
        $user = $this->show($userId);
        (new UserRepository)->update($user, $data);
        return $user;
    }
}