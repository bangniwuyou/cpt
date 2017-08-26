<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/25
 * Time: 17:00
 */

namespace www\controllers;


use common\base\IController;


/**
 * Class SiteController
 * @package web\controllers
 * @author 姜海强 <jhq0113@163.com>
 */
class SiteController extends IController
{
    /**
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actions()
    {
        return [
            'error'=>[
                'class'=>'yii\web\ErrorAction',
            ]
        ];
    }

    /**首页
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}