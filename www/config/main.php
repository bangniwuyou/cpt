<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-www',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute'=>'site',
    'controllerNamespace' => 'www\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-www',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'user' => [
            'identityClass' => 'common\service\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-www', 'httpOnly' => true],
        ],
        'urlManager' => [
            'enableStrictParsing' => false,
            'rules' => require (__DIR__.'/rules.php'),
        ],
    ],
    'params' => $params,
];
