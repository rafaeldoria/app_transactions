<?php

namespace App\Repositories;

use Exception;
use App\Models\User;

class UserRepository extends BaseRepository
{
    public function index()
    {
        $user = new User();
        return $user->all();
    }

    public function show(Int $userId)
    {
        $user = new User();
        $user = $user->find($userId);
        if (!$user) {
            throw new Exception('Not found', -404);
        }
        return $user;
    }

    public function store(array $data)
    {
        $user = new User();
        return $user->create($data);
    }

    public function update($user, array $data)
    {
        $user->update($data);
    }

    public function destroy($user)
    {
        $user->delete();
    }
}
