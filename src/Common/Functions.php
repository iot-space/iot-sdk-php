<?php

function iotApp($abstract = null, array $parameters = [])
{
    $appInstance = \IotSpace\IotApplication::getInstance();

    if (is_null($abstract)) {
        return $appInstance;
    }

    return $appInstance->make($abstract, $parameters);
}

/**
 * 返回当前的毫秒时间戳
 * @return float
 */
function getMicroTime()
{
    list($mSec, $sec) = explode(' ', microtime());
    $mSecTime = (float)sprintf('%.0f', (floatval($mSec) + floatval($sec)) * 1000);
    return $mSecTime;
}
