<?php
/**
 * 后台基控制器
 * User: 姜海强
 * Date: 2016/5/21 0021
 * Time: 14:34
 */
namespace backend\controllers;

use backend\components\AccessFilter;
use backend\service\BaseService;
use backend\service\MenuService;
use backend\service\UserService;
use common\base\IController;
use common\utils\ComHelper;

class  BackendController extends IController
{
    /**登录用户
     * @var
     * @author 姜海强 <jhq0113@163.com>
     */
    public $user;

    /**模块Id
     * @var
     * @author 姜海强 <jhq0113@163.com>
     */
    public $moduleId;

    /**控制器Id
     * @var
     * @author 姜海强 <jhq0113@163.com>
     */
    public $controllerId;

    /**操作Id
     * @var
     * @author 姜海强 <jhq0113@163.com>
     */
    public $actionId;

    /**a标签状态
     * @var
     * @author 姜海强 <jhq0113@163.com>
     */
    public $status;

    /**实体id
     * @var
     * @author 姜海强 <jhq0113@163.com>
     */
    public $entityId;

    /**默认使用服务
     * @var   BaseService
     * @author 姜海强 <jhq0113@163.com>
     */
    public $defaultService;

    /**Action过滤，子类重写需要将此配置引用
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function behaviors()
    {
        return [
            [
                'class' => 'backend\components\AccessFilter',
                'only'  => ['*']
            ],
        ];
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
	public function beforeAction($action)
	{
	    //清除所有缓存
	    //MenuService::self()->delAllAdminMenu();

        $this->status=ComHelper::fInt('status');
        $this->entityId=ComHelper::fInt('id');

	    $this->moduleId=\Yii::$app->controller->module->id;
	    $this->controllerId=\Yii::$app->controller->id;
	    $this->actionId=\Yii::$app->controller->action->id;
        $login=$this->checkLogin();

	    if($login)
        {
            $this->loadParamsForView();
            return parent::beforeAction($action);
        }
		$this->redirect(['/login/index']);
	}

    /**为视图加载参数
     * @author 姜海强 <jhq0113@163.com>
     */
	private function loadParamsForView()
    {
        \Yii::$app->view->params[AccessFilter::MENU]=MenuService::self()->getMenuByAdminId($this->user['id']);
        if(!empty(\Yii::$app->view->params[AccessFilter::MENU]))
        {
            $modules=array_column(\Yii::$app->view->params[AccessFilter::MENU],'description','name');
            \Yii::$app->view->params['moduleName']=isset($modules[$this->moduleId]) ? $modules[$this->moduleId] : '直播+';
        }
        \Yii::$app->view->params[AccessFilter::MODULE_ID]=$this->moduleId;
        \Yii::$app->view->params[AccessFilter::CONTROLLER_ID]=$this->controllerId;
        \Yii::$app->view->params[AccessFilter::ACTION_ID]=$this->actionId;
        \Yii::$app->view->params['user']=$this->user;
    }

    /**添加管理员操作日志
     * @param int     $operateId   操作ID
     * @param string  $desc        操作描述
     * @author 姜海强 <jhq0113@163.com>
     */
    public function addOperateLog($operateId,$desc='')
    {
        BaseService::addOperateLog($this->user,$operateId,$desc);
    }

    /**后台首页
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**检查是否登录
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public function checkLogin()
    {
        $this->user=UserService::self()->getLoginInfo();
        if(!empty($this->user) && is_array($this->user))
        {
            return true;
        }
        return false;
    }

    /**启用状态设置
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionOn()
    {
        $this->defaultService->on($this->entityId,$this->status);
        MenuService::self()->delAllAdminMenu();
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**删除元素
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionDel()
    {
        $this->defaultService->delById($this->entityId);
        MenuService::self()->delAllAdminMenu();
        $this->redirect($_SERVER['HTTP_REFERER']);
    }
}