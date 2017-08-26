<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/25
 * Time: 15:23
 */

namespace console\controllers;


class TestController extends TaskController
{
    /**
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionIndex()
    {
        echo $this->route;
    }

    /**秒级定时任务
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionSecond()
    {
        $this->executeSecondTask(function($controller){
            $controller->writeLog('。。。。。');
        },5);
    }
}