<?php


namespace IotSpace\Ty;

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
    const CACHE_TOKEN_KEY = 'TY_ACCESS_TOKEN';

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
        $this->config = $this->app['config']['ty'];
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

    protected function getHeaders(bool $withToken = true)
    {
        $timestamp = getMicroTime();

        $clientId = $this->config['client_id'];
        $secret = $this->config['secret'];
        if(empty($clientId)){
            throw new IotException('缺少TY_CLIENT_ID配置', ErrorCode::OPTIONS);
        }
        if(empty($secret)){
            throw new IotException('缺少TY_SECRET配置', ErrorCode::OPTIONS);
        }

        if($withToken){
            $data = $clientId . $this->getCacheToken() . $timestamp;
        }else{
            $data = $clientId . $timestamp;
        }

        $hash = hash_hmac("sha256", $data, $secret);
        $sign = strtoupper($hash);

        $headers = [
            'client_id' => $clientId,
            'sign' => $sign,
            't' => $timestamp,
            'sign_method' => 'HMAC-SHA256'
        ];

        if($withToken){
            $headers['access_token'] = $this->getCacheToken();
            $headers['Content-Type'] = 'application/json';
        }

        return $headers;

    }

    /**
     * @param $url
     * @param string $method
     * @param bool $withToken
     * @param null $body
     * @return mixed
     * @throws IotException
     */
    protected function getHttpRequest($url, $method = HttpMethod::GET, bool $withToken = true, $body = null)
    {
        $url = $this->config['api'].$url;
        $headers = $this->getHeaders($withToken);
        $options = [
            'headers' => $headers
        ];
        if($body){
            $options['body'] = json_encode($body, JSON_UNESCAPED_UNICODE);
        }
        $res = ApiRequest::httpRequest($method, $url, $options);
        $context = [
            "platform"=>Platform::TY,
            "method"=>$method,
            "url"=>$url,
            "res_code"=>$res['code']??"",
            "res_data"=>var_export($res, true),
            "post_data"=>var_export($options, true),
            "message"=>$res['msg']??"",
            "createtime"=>date('Y-m-d H:i:s'),
        ];
        $this->log->info('ok', $context);

        if(!$res['success']){
            throw new IotException($res['msg'], ErrorCode::TY, $res);
        }
        $res = $res['result'];
        return $res;
    }
}
