# iot-sdk-php

**涂鸦云、萤石云开发 PHP SDK**

* 初始化（放在应用程序入口处，只需要执行一次）
```
$iotConfig = [
    'ty' => [
        'client_id' => Env::get('iot.TY_CLIENT_ID', ''),
        'secret' => Env::get('iot.TY_SECRET', ''),
        'schema' => Env::get('iot.TY_SCHEMA', ''),
        'api' => Env::get('iot.TY_API', 'https://openapi.tuyacn.com')
    ],
    'ys' => [
        'key' => Env::get('iot.YS_KEY', ''),
        'secret' => Env::get('iot.YS_SECRET', ''),
        'api' => Env::get('iot.YS_API', 'https://open.ys7.com'),
        'es_key' => Env::get('iot.ES_KEY', ''),
        'es_secret' => Env::get('iot.ES_SECRET', ''),
        'es_api' => Env::get('iot.ES_API', 'https://esopen.ys7.com')
    ]
];
$iotApp = new IotApplication($iotConfig);
$iotApp['cache'] = $iotApp->make(ThinkCache::class);
$iotApp['log'] = $iotApp->make(ThinkLog::class);
```

* API方法调用
```
$token = TyCloud::TokenClient()->getToken();
$device = TyCloud::DeviceClient()->getDevice('60742034d8bfc0df40d3');
......
```


**Laravel请使用iot-sdk-php-laravel**

https://github.com/iot-space/iot-sdk-php-laravel