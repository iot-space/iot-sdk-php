<?php

namespace IotSpace\Cache;

interface ICache
{
    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key):bool;

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     * @param $value
     * @param int $ttl(秒)
     * @return bool
     */
    public function add(string $key, $value, int $ttl=0):bool;

    /**
     * @param string $key
     * @return bool
     */
    public function remove(string $key):bool;
}