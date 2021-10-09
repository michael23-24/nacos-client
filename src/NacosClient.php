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
namespace Michael23\Nacos;

use GuzzleHttp\RequestOptions;

class NacosClient extends AbstractProvider
{
    public function __construct(array $config = [])
    {
        parent::__construct(new Config($config));
    }

    /**
     * @param $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'clusters' => '', // 集群名称(字符串，多个集群用逗号分隔)
     *     'healthyOnly' => false,
     * ]
     */
    public function lists($serviceName, array $optional = [])
    {
        return $this->request('GET', '/nacos/v1/ns/instance/list', [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
            ])),
        ]);
    }

    public function login($username, $password)
    {
        return $this->client()->request('POST', '/nacos/v1/auth/users/login', [
            RequestOptions::QUERY => [
                'username' => $username,
                'password' => $password,
            ],
        ]);
    }
}
