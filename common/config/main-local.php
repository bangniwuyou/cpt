<?php
return [
    'components' => [
        //一主多从支持
        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
            'tablePrefix'=>'cpt_',
            'serverRetryInterval'=>60,
            'username' => 'cpt',
            'password' => '123456',
            'dsn' => 'mysql:host=127.0.0.1;port=3336;dbname=qiang_cpt',
            'slaves'=>[
                ['dsn' => 'mysql:host=127.0.0.1;port=3336;dbname=qiang_cpt'],
            ],
            'slaveConfig'=>[
                'username' => 'cpt',
                'password' => '123456',
                'attributes' => [
                    PDO::ATTR_TIMEOUT => 30,
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
