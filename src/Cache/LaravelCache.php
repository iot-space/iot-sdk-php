<?php


namespace IotSpace\Cache;

use Illuminate\Support\Facades\Cache;

class LaravelCache implements ICache
{
    public function has(string $key): bool
    {
        return Cache::has($key);
    }

    public function get(string $key)
    {
        return Cache::get($key);
    }

    public function add(string $key, $value, int $ttl = 0): bool
    {
        return Cache::put($key, $value, $ttl);
    }

    public function remove(string $key): bool
    {
        return Cache::forever($key);
    }
}