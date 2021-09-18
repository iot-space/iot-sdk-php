<?php

namespace IotSpace\Ys;

use IotSpace\Exception\ErrorCode;
use IotSpace\Exception\IotException;
use IotSpace\Support\HttpMethod;

/**
 * 授权管理
 * https://www.yuque.com/u1400669/kb/gefpbg
 * @package IotSpace\Ys
 */
class EsTokenClient extends EsBaseClient
{
    /**
     * 获取令牌
     * @return string
     * @throws \IotSpace\Exception\IotException
     */
    public function getToken(): string
    {
        if ($this->cache->has(self::ES_CACHE_TOKEN_KEY)) {
            $token = $this->cache->get(self::ES_CACHE_TOKEN_KEY);
            return $token;
        }

        $url = '/api/user/open-app/auth/gettoken';

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
        $expiresIn = $data['expiresIn']; //Token过期时间  毫秒时间戳

        $ttl = $expiresIn-60;

        $this->cache->add(self::ES_CACHE_TOKEN_KEY, $accessToken, $ttl);

        return $accessToken;
    }

}
