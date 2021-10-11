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

require '../vendor/autoload.php';
require '../src/Config.php';
require '../src/AbstractProvider.php';
require '../src/NacosClient.php';


$application = new \MicroTool\Nacos\NacosClient([
    'base_uri'      => 'http://localhost:8848',
    'username'      => 'nacos',
    'password'      => 'nacos',
    'guzzle_config' => [
        'headers' => [
            'charset' => 'UTF-8',
        ],
    ],
]);

$response    = $application->lists('test', ['groupName' => 'test', 'namespaceId' => 'test']);
$result      = json_decode((string)$response->getBody(), true);
$serviceNode = [];
foreach ($result['hosts'] as $service) {
    $serviceNode[] = [
        $service['ip'],
        $service['port'],
    ];
}

var_dump($serviceNode);


