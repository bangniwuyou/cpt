<?php
/**
 * 管理员控制器
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/12/3
 * Time: 10:54
 */

namespace backend\controllers;

use backend\service\MenuService;
use backend\service\RoleService;
use backend\service\UserService;
use common\utils\ComHelper;
use yii\helpers\ArrayHelper;

class AdminController extends BackendController
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->defaultService=UserService::self();
    }

    public function actionIndex()
    {
        $list=$this->defaultService->getAll();
        return $this->render('index',['list'=>$list]);
    }

    public function actionAdd()
    {
        if(\Yii::$app->request->isGet)
        {
            return $this->render('form');
        }
        elseif(isset($_POST['userName'],$_POST['trueName'],$_POST['password'],$_POST['isOn']))
        {
            $admin[':user_name']=ComHelper::fStringP('userName');
            $admin[':true_name']=ComHelper::fStringP('trueName');
            $admin[':password']=ComHelper::fStringP('password');
            $admin[':is_on']=ComHelper::fIntP('isOn');
            $this->defaultService->save($admin);

            $this->addOperateLog(4,'添加管理员【'.$admin[':user_name'].'】');
            ComHelper::retData();
        }
    }

    public function actionEdit()
    {
        if(isset($_GET['id']))
        {
            $id=ComHelper::fIntG('id');
            if($id>0)
            {
                $info=$this->defaultService->getInfo($id);
                if(!empty($info))
                {
                    return $this->render('form',['info'=>$info]);
                }
            }
        }
        elseif (isset($_POST['id'],$_POST['userName'],$_POST['trueName'],$_POST['isOn']))
        {
            $admin[':id']=ComHelper::fIntP('id');
            $admin[':user_name']=ComHelper::fStringP('userName');
            $admin[':true_name']=ComHelper::fStringP('trueName');
            $admin[':is_on']=ComHelper::fIntP('isOn');
            $this->defaultService->save($admin);
            ComHelper::retData();
        }
    }

    /**设置超级管理员
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionSuper()
    {
        $this->defaultService->super($this->entityId,$this->status);
        $desc=empty($this->status) ? '取消管理员【'.$this->entityId.'】的超级管理员' : '设置管理员【'.$this->entityId.'】为超级管理员';
        $this->addOperateLog(7,$desc);
        MenuService::self()->delAllAdminMenu();
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**修改密码
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionPassword()
    {
        if(isset($_GET['id']))
        {
            $id=ComHelper::fIntG('id');
            $userName=ComHelper::fStringG('userName');
            if($id>0)
            {
                return $this->render('password',['id'=>$id,'userName'=>$userName]);
            }
        }
        elseif (isset($_POST['id'],$_POST['password']))
        {
            $admin[':id']=ComHelper::fIntP('id');
            $admin[':password']=ComHelper::fStringP('password');
            $this->defaultService->updatePassword($admin);
            $this->addOperateLog(6,'重置管理员【'.$admin[':id'].'】密码');
            MenuService::self()->delAllAdminMenu();
            ComHelper::retData();
        }
    }

    /**管理员角色分配
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionUserRole()
    {
        if(isset($_GET['id']))
        {
            $id=ComHelper::fIntG('id');
            if($id>0)
            {
                $userName=ComHelper::fStringG('userName');
                $ownList=$this->defaultService->getUserRoleList($id);
                $list=RoleService::self()->getAllEnableList();
                if(!empty($list))
                {
                    $list=ArrayHelper::index($list,'id');
                }
                return $this->render('user-role',['ownList'=>$ownList,'list'=>$list,'id'=>$id,'userName'=>$userName]);
            }
        }
        elseif (isset($_POST['id']))
        {
            $id=ComHelper::fIntP('id');
            $list=isset($_POST['list']) ? $_POST['list'] : [];
            if($id>0)
            {
                $this->defaultService->setUserRole($id,$list);
                MenuService::self()->delAllAdminMenu();
                ComHelper::retData();
            }
        }
    }

    /**删除元素
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionDel()
    {
        $this->defaultService->delById($this->entityId);
        $this->addOperateLog(5,'删除管理员【'.$this->entityId.'】');
        MenuService::self()->delAllAdminMenu();
        $this->redirect($_SERVER['HTTP_REFERER']);
    }
}