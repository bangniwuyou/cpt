<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

$rootDir=dirname(__DIR__);
require($rootDir . '/../vendor/autoload.php');
require($rootDir . '/../vendor/yiisoft/yii2/Yii.php');
require($rootDir . '/../common/config/bootstrap.php');
require($rootDir . '/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

(new yii\web\Application($config))->run();
