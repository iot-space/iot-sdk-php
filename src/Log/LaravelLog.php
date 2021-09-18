<?php


namespace IotSpace\Log;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaravelLog implements ILog
{

    public function error($message, $context = [])
    {
        Log::error($message, $context);
    }

    public function info($message, $context = [])
    {
        Log::info($message, $context);
        //保存数据库日志(DB中必须先创建此表)，如果不需要，请注释此行
//        CREATE TABLE `xx_iot_log` (
//            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
//            `platform` VARCHAR(32) NOT NULL COLLATE 'utf8mb4_unicode_ci',
//            `method` VARCHAR(32) NOT NULL COLLATE 'utf8mb4_unicode_ci',
//            `url` VARCHAR(191) NOT NULL COLLATE 'utf8mb4_unicode_ci',
//            `post_data` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
//            `res_code` VARCHAR(64) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
//            `res_data` LONGTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
//            `message` VARCHAR(191) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
//            `createtime` DATETIME NOT NULL,
//            PRIMARY KEY (`id`) USING BTREE
//        )
//        COMMENT='IOT云接口日志表'
//        COLLATE='utf8mb4_unicode_ci'
//        ENGINE=InnoDB
//        ;

        DB::table('iot_log')->insert($context);
    }
}