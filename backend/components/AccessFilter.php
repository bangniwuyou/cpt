<?php
/**
 * 权限过滤器
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/11/29
 * Time: 11:14
 */

namespace backend\components;

use common\utils\ComHelper;
use yii\base\ActionFilter;

class AccessFilter extends ActionFilter
{
    const MENU='accessMenu';

    const MODULE_ID='accessModuleId';

    const CONTROLLER_ID='accessControllerId';

    const ACTION_ID='accessActionId';

    /**校验访问权限
     * @param \yii\base\Action $action
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public function beforeAction($action)
    {
        $result=parent::beforeAction($action);

        if(!$result)
        {
            return false;
        }
        $access=$this->checkAccess();
        if(!$access)
        {
            if(\Yii::$app->request->isAjax || (ComHelper::fIntP('isAjax') == 1))
            {
                ComHelper::retState(StateHelper::$ERROR_RIGHT);
            }
            \Yii::$app->response->redirect(['/site/forbidden','url'=>empty($_SERVER['HTTP_REFERER']) ? '/' : $_SERVER['HTTP_REFERER']]);
            \Yii::$app->response->send();
            exit();
        }
        return true;
    }

    /**是否有权访问
     * @author 姜海强 <jhq0113@163.com>
     */
    protected function checkAccess()
    {
        $params=\Yii::$app->view->params;

        if($params['user']['isSuperAdmin'] == 1)//超级管理员
        {
            return true;
        }
        if(is_array($params[self::MENU]) && !empty($params[self::MENU]))
        {
            foreach ($params[self::MENU] as $key=>$module)
            {
                if($module['name'] == $params[self::MODULE_ID])   //验证模块
                {
                    //模块默认首页给予权限
                    if(\Yii::$app->controller->module->defaultRoute == $params[self::CONTROLLER_ID] && $params[self::ACTION_ID] =='index')
                    {
                        return true;
                    }
                    if(isset($module['controllerList']) && is_array($module['controllerList']) && !empty($module['controllerList']))
                    {
                        foreach ($module['controllerList'] as $controller)
                        {
                            if($controller['name'] == $params[self::CONTROLLER_ID])  //验证控制器
                            {
                                if(isset($controller['actionList']) && is_array($controller['actionList']) && !empty($controller['actionList']))
                                {
                                    foreach ($controller['actionList'] as $action)
                                    {
                                        if($action['name'] == $params[self::ACTION_ID])  //验证操作
                                        {
                                            return true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;
    }
}