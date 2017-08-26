<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-rest',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute'=>'rest',
    'controllerNamespace' => 'rest\controllers',
    'components' => [
        'errorHandler' => [
            'errorAction' => 'rest/error',
        ],
        'urlManager' => [
            'enableStrictParsing' => true,
            'rules' => require (__DIR__.'/rules.php'),
        ],
        'user' => [
            'identityClass' => 'common\service\User',
            'enableAutoLogin' => false,
            'identityCookie' => ['name' => '_identity-rest', 'httpOnly' => true],
        ],
    ],
    'modules'=>[
        'v1'=>[
            'class'=>'rest\modules\v1\Module'
        ],
    ],
    'params' => $params,
];
