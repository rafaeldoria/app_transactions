<?php

namespace App\Services\Redis;

use Illuminate\Support\Facades\Redis;

class RedisService
{
    public function exists(string $key)
    {
        return Redis::exists($key);
    }

    public function set(string $key, $data, int $time = 20)
    {
        Redis::set($key, $data, 'EX', $time);
    }

    public function get(string $key)
    {
        return Redis::get($key);
    }
}