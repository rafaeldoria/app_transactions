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

    public function show($id)
    {
        return (new UserRepository)->show($id);
    }

    public function store(array $data)
    {
        $user = (new UserRepository)->store($data);
        (new CreateWalletService)->createWallet($user);
        return $user;
    }

    public function update(int $id, $data)
    {
        $user = $this->show($id);
        (new UserRepository)->update($user, $data);
        return $user;
    }

    public function destroy(int $id)
    {
        $user = $this->show($id);
        (new UserRepository)->destroy($user);
        return new User;
    }
    
}