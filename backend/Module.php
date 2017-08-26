<?php

namespace backend;

/**
 * app module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\controllers';

    public $defaultRoute='backend';
}
