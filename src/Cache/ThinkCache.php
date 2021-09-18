<?php


namespace IotSpace\Cache;

use think\Cache;

class ThinkCache implements ICache
{
    public function has(string $key): bool
    {
        return Cache::get($key);
    }

    public function get(string $key)
    {
        return Cache::get($key);
    }

    public function add(string $key, $value, int $ttl = 0): bool
    {
        return Cache::set($key, $value, $ttl);
    }

    public function remove(string $key): bool
    {
        return Cache::rm($key);
    }
}