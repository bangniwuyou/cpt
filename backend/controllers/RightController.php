<?php
/**
 * 权限节点管理
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/11/29
 * Time: 16:42
 */

namespace backend\controllers;


use backend\service\MenuService;
use backend\service\RightService;
use common\utils\ComHelper;

class RightController extends BackendController
{
    /**初始化
     * @author 姜海强 <jhq0113@163.com>
     */
    public function init()
    {
        parent::init();
        $this->defaultService=RightService::self();
    }

    public function actionIndex()
    {
        $moduleName=ComHelper::fString('moduleName');
        $rightList=RightService::self()->getAll();

        if(!empty($rightList))
        {
            $rightList=MenuService::rightListToMenu($rightList);
            if(!empty($moduleName))
            {
                $rightList=MenuService::self()->filterByName($rightList,$moduleName);
            }
        }

        return $this->render('index',['list'=>$rightList,'moduleName'=>$moduleName]);
    }

    /**删除节点
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionDel()
    {
        if(isset($_POST['id']))
        {
            $id=ComHelper::fIntP('id');
            if($id>0)
            {
                RightService::self()->delById($id);
                MenuService::self()->delAllAdminMenu();
                ComHelper::retData();
            }
        }
    }

    /**添加节点列表
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionAdd()
    {
        if(\Yii::$app->request->isGet)
        {
            $rightList=RightService::self()->getAllEnableList();
            return $this->render('add',['rightList'=>$rightList]);
        }
        elseif(isset($_POST['addArray']))
        {
            $addArray=$_POST['addArray'];
            RightService::self()->addList($addArray);
            MenuService::self()->delAllAdminMenu();
            ComHelper::retData();
        }
    }

    /**编辑
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionEdit()
    {
        if(isset($_GET['id']))
        {
            $id=ComHelper::fIntG('id');
            $info=RightService::self()->getInfo($id);
            if(!empty($info))
            {
                $rightList=RightService::self()->getAllEnableList();
                return $this->render('edit',['info'=>$info,'rightList'=>$rightList]);
            }
        }
        elseif (isset($_POST['id'],$_POST['nodeName'],$_POST['desc'],$_POST['level'],$_POST['parentId'],$_POST['sort'],$_POST['isOn']))
        {
            $info['id']=ComHelper::fIntP('id');
            $info['level']=ComHelper::fIntP('level');
            $info['parentId']=ComHelper::fIntP('parentId');
            $info['sort']=ComHelper::fIntP('sort');
            $info['nodeName']=ComHelper::fStringP('nodeName');
            $info['desc']=ComHelper::fStringP('desc');
            $info['isOn']=ComHelper::fIntP('isOn');
            $info['isShow']=ComHelper::fIntP('isShow');
            RightService::self()->update($info);
            MenuService::self()->delAllAdminMenu();
            ComHelper::retData();
        }
    }

    /**设置是否显示
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionShow()
    {
        RightService::self()->show($this->entityId,$this->status);
        MenuService::self()->delAllAdminMenu();
        $this->redirect($_SERVER['HTTP_REFERER']);
    }
}