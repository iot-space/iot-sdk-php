<?php


namespace IotSpace\Ys;

use IotSpace\Cache\ICache;
use IotSpace\IotApplication;
use IotSpace\Log\ILog;
use IotSpace\Support\ApiRequest;
use IotSpace\Exception\IotException;
use IotSpace\Exception\ErrorCode;
use IotSpace\Support\HttpMethod;
use IotSpace\Support\Platform;

abstract class BaseClient
{
    const CACHE_TOKEN_KEY = 'YS_ACCESS_TOKEN';

    /**
     * @var IotApplication
     */
    protected $app;
    /**
     * @var array
     */
    protected $config;
    /**
     * @var ICache
     */
    protected $cache;
    /**
     * @var ILog
     */
    protected $log;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->app = iotApp();
        $this->config = $this->app['config']['ys'];
        $this->cache = $this->app['cache'];
        $this->log = $this->app['log'];
    }

    protected function getCacheToken()
    {
        if($this->cache->has(self::CACHE_TOKEN_KEY)){
            return $this->cache->get(self::CACHE_TOKEN_KEY);
        }else{
            $token = iotApp(TokenClient::class)->getToken();
            return $token;
        }
    }

    protected function getHeaders()
    {
        $headers = [
            'Host' => 'open.ys7.com',
            'Content-Type' => 'application/x-www-form-urlencoded',
//            'Content-Type' => 'application/json'
        ];

        return $headers;

    }

    /**
     * @param $url
     * @param array $postData
     * @param string $method
     * @param bool $withToken
     * @param bool $withHeaders
     * @return mixed
     * @throws IotException
     */
    protected function getHttpRequest($url, array $postData, $method = HttpMethod::POST, bool $withToken=true, bool $withHeaders=true)
    {
        $url = $this->config['api'].$url;
        $options = [];
        if($withHeaders){
            $options['headers'] = $this->getHeaders();
        }
        if($withToken){
            $postData['accessToken'] = $this->getCacheToken();
        }
        if($postData){
            $options['form_params'] = $postData;
        }
        $res = ApiRequest::httpRequest($method, $url, $options);

        $context = [
            "platform"=>Platform::YS,
            "method"=>$method,
            "url"=>$url,
            "res_code"=>$res['code']??"",
            "res_data"=>var_export($res, true),
            "post_data"=>var_export($options, true),
            "message"=>$res['msg']??"",
            "createtime"=>date('Y-m-d H:i:s'),
        ];
        $this->log->info('ok', $context);
        if((int)$res['code'] !== 200){
            throw new IotException($res['msg'], ErrorCode::YS, $res);
        }
        return $res['data']??true;
    }
}
