<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function index()
    {
        return User::all();
    }

    public function show(Int $id)
    {
        $user = User::find($id);
        if (!$user) {
            throw new \Exception('Not found', -404);
        }
        return $user;
    }

    public function store(array $data)
    {
        return User::create($data);
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
