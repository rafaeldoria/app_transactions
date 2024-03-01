<?php

namespace App\Services\Users;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Redis\RedisService;
use Illuminate\Support\Facades\Redis;
use App\Services\Wallets\CreateWalletService;

class UserService
{
    protected $redis;

    public function __construct()
    {
        $this->redis = new RedisService();
    }
    
    public function index()
    {
        $key = 'user.service.repository.index';
        if(!$this->redis->exists($key) || app()->environment() === 'testing'){
            
            $user = (new UserRepository)->index();
            if(!app()->environment() === 'testing'){
                $this->redis->set($key, $user, 30);
            }
            return $user;
        }
        $dataArray = json_decode($this->redis->get($key), true);
        return collect($dataArray);
    }

    public function show($userId)
    {
        $key = 'user.service.repository.show'.$userId;
        if(!$this->redis->exists($key) || app()->environment() === 'testing'){
            $user = (new UserRepository)->show($userId);

            if(!app()->environment() === 'testing'){
                $this->redis->set($key, $user, 30);
            }

            return $user;
        }
        $dataArray = json_decode($this->redis->get($key), true);
        return collect($dataArray);
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
        if(!$user instanceof User){
            $dataArray = $user;
            if(!is_array($user)){
                $dataArray = json_decode($user, true);
            }
            $user = new User();
            $user->fill($dataArray);
            $user->id = $dataArray['id'];
        }
        (new UserRepository)->update($user, $data);
        return $user;
    }
}