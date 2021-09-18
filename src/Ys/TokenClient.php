<?php

namespace IotSpace\Ys;

use IotSpace\Exception\IotException;
use IotSpace\Exception\ErrorCode;
use IotSpace\Support\HttpMethod;

/**
 * 授权管理
 * https://open.ys7.com/doc/zh/book/index/user.html
 * @package IotSpace\Ys
 */
class TokenClient extends BaseClient
{
    /**
     * 获取令牌
     * @return string
     * @throws \IotSpace\Exception\IotException
     */
    public function getToken(): string
    {
        $url = '/api/lapp/token/get';

        if ($this->cache->has(self::CACHE_TOKEN_KEY)) {
            $token = $this->cache->get(self::CACHE_TOKEN_KEY);
            return $token;
        }

        $key = $this->config['key'];
        $secret = $this->config['secret'];
        if(empty($key)){
            throw new IotException('缺少YS_KEY配置', ErrorCode::OPTIONS);
        }

        if(empty($secret)){
            throw new IotException('缺少YS_SECRET配置', ErrorCode::OPTIONS);
        }

        $postData = [
            'appKey' => $key,
            'appSecret' => $secret
        ];

        $data = $this->getHttpRequest($url, $postData, HttpMethod::POST, false, true);

        $accessToken = $data['accessToken'];
        $expireTime = $data['expireTime']; //Token过期时间  毫秒时间戳

        $ttl = $expireTime/1000-time()-60;

        $this->cache->add(self::CACHE_TOKEN_KEY, $accessToken, $ttl);

        return $accessToken;
    }

}
