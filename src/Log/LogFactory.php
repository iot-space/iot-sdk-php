<?php

namespace IotSpace\Log;

class LogFactory
{

    private static $log;

    private function __construct()
    {

    }

    /**
     * 初始化静态类
     *
     * @return ILog
     */
    public static function log(): ILog
    {
        if(empty(self::$log))
        {
            if(class_exists(Illuminate\Log\LogManager::class)){
                self::$log = new LaravelLog();
            }elseif (class_exists(think\Log::class)){
                self::$log = new ThinkLog();
            }
            throw new \Exception('Log class not found.');
        }

        return self::$log;
    }
}