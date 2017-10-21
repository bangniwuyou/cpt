<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/7
 * Time: 13:46
 */

namespace backend\controllers;


use backend\models\ar\AdminOperate;
use yii\web\Controller;

class ToolController extends BackendController
{

    /**summer编辑器
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionSummer()
    {
        $model = new AdminOperate();
        return $this->render('summer',['model'=>$model]);
    }
}