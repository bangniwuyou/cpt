<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-file',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'file\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-file',
        ],
        'errorHandler' => [
            'errorAction' => 'base/error',
        ],
        'urlManager' => [
            'enableStrictParsing' => false,
            'rules' => require (__DIR__.'/rules.php'),
        ],
    ],
    'params' => $params,
];
