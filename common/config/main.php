<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false
        ],
        //手机发送短信
        'phoneMsg'=>[
            'class'=>'web\modules\v1\service\PhoneMessage',
            'defaultServiceConfig'=>[
                //阿里大鱼
                'class'=>'common\service\message\AliDayu',
                'appKey'=>'',
                'secretKey'=>'',
                'templateCode'=>'',
                'signName'=>'阿里云短信测试专用'
            ],
            'ipDayLimit'=>150,
            'length'=>4,
            'seed'=>'1234567890abcdef'
        ]
    ],
];
