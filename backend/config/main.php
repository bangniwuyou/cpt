<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'name'=>'cpt后台管理系统',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'defaultRoute'=>'backend',
    'homeUrl'=>'/backend',
    'controllerNamespace' => 'backend\controllers',
    'components' => [
        'session' => [
            'class' => 'backend\components\Session',
        ],
        'fileCache'=>[
            'class'=>'yii\caching\FileCache',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => [
                        'position' => \yii\web\View::POS_HEAD,
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'jsOptions' => [
                        'position' => \yii\web\View::POS_HEAD,
                    ]
                ],

            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'errorHandler' => [
            'errorAction' => 'base/error',
        ],
    ],
    'modules'=>[
        //后台
        'backend'=>[
            'class'=>'backend\Module'
        ],
    ],
    'params' => $params,
];
