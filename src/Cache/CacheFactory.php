<?php


namespace IotSpace\Cache;


class CacheFactory
{

    private static $cache;

    private function __construct()
    {

    }

    /**
     * 初始化静态类
     *
     * @return ICache
     */
    public static function cache(): ICache
    {
        if(empty(self::$cache))
        {
            if(class_exists(Illuminate\Cache\CacheManager::class)){
                self::$cache = new LaravelCache();
            }elseif (class_exists(think\Cache::class)){
                self::$cache = new ThinkCache();
            }
            throw new \Exception('Cache class not found.');
        }

        return self::$cache;
    }
}