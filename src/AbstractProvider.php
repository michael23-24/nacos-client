<?php
// +----------------------------------------------------------------------
// | 注释
// +----------------------------------------------------------------------
// | Copyright (c) 义幻科技 http://www.mobimedical.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Michael23
// +----------------------------------------------------------------------
// | date: 2021-10-09
// +----------------------------------------------------------------------
namespace MicroTool\Nacos;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractProvider
{
    /**
     * @var null|string
     */
    private $accessToken;

    /**
     * @var int
     */
    private $expireTime = 0;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function request($method, $uri, array $options = [])
    {
        $token = $this->getAccessToken();
        $token && $options[RequestOptions::QUERY]['accessToken'] = $token;
        return $this->client()->request($method, $uri, $options);
    }

    public function client()
    {
        $config = array_merge($this->config->getGuzzleConfig(), [
            'base_uri' => $this->config->getBaseUri(),
        ]);

        return new Client($config);
    }

    protected function getAccessToken()
    {
        $username = $this->config->getUsername();
        $password = $this->config->getPassword();

        if ($username === null || $password === null) {
            return null;
        }

        if (!$this->isExpired()) {
            return $this->accessToken;
        }

        $result = $this->handleResponse($this->login($username, $password));

        $this->accessToken = $result['accessToken'];
        $this->expireTime  = $result['tokenTtl'] + time();

        return $this->accessToken;
    }

    protected function handleResponse(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        $contents   = (string)$response->getBody();
        if ($statusCode !== 200) {
            throw new \Exception($contents, $statusCode);
        }
        return \GuzzleHttp\json_decode($contents, true);
    }

    protected function isExpired()
    {
        if (isset($this->accessToken) && $this->expireTime > time() + 60) {
            return false;
        }
        return true;
    }

    protected function filter(array $input)
    {
        $result = [];
        foreach ($input as $key => $value) {
            if ($value !== null) {
                $result[$key] = $value;
            }
        }

        return $result;
    }


}
