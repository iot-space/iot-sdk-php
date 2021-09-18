<?php

namespace IotSpace\Ty;

use IotSpace\Support\HttpMethod;

/**
 * 授权管理
 * https://developer.tuya.com/cn/docs/cloud/oauth-management?id=K95ztzpoll7v5
 * @package IotSpace\Ty
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
        $url = '/v1.0/token?grant_type=1';

        if ($this->cache->has(self::CACHE_TOKEN_KEY)) {
            $token = $this->cache->get(self::CACHE_TOKEN_KEY);
            return $token;
        }

        $data = $this->getHttpRequest($url, HttpMethod::GET, false);

        $accessToken = $data['access_token'];
        $expireTime = $data['expire_time']; //Token过期时间  秒
        $ttl = $expireTime-60;
        $this->cache->add(self::CACHE_TOKEN_KEY, $accessToken, $ttl);
        return $accessToken;
    }

    public function refreshToken($refreshToken)
    {
        $url = "/v1.0/token/{$refreshToken}";

        $data = $this->getHttpRequest($url, HttpMethod::GET, false);

        $accessToken = $data['access_token'];
        $expireTime = $data['expire_time']; //Token过期时间  秒
        $ttl = $expireTime-60;

        $this->cache->add(self::CACHE_TOKEN_KEY, $accessToken, $ttl);
        return $accessToken;
    }

}
