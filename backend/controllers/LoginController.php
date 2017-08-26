<?php
/**
 * 登陆控制器
 * User: 姜海强
 * Date: 2016/7/22
 * Time: 19:43
 */
namespace backend\controllers;

use backend\components\StateHelper;
use backend\service\UserService;
use common\utils\ComHelper;
use common\utils\SNHelper;
use yii\web\Controller;

class LoginController extends Controller
{
	public $layout='login';

	const KEY='sd3eas234sdfasfasdfdfdfsaSDFSFs_FOR_SWAGGER';

    /**
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
	public function actions()
    {
        return [
            'verify' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

	/**
	 * 登陆
	 */
	public function actionIndex()
	{
		return $this->render('login');
	}

    /**
     * @author 姜海强 <jhq0113@163.com>
     */
	public function actionLogin()
    {
        if(isset($_POST['userName'],$_POST['password'],$_POST['code']))
        {
            $verify=ComHelper::fStringP('code');
            if(\Yii::$app->controller->createAction('verify')->validate($verify,false))
            {
                $userName=ComHelper::fStringP('userName');
                $password=ComHelper::fStringP('password');
                $result=UserService::self()->login($userName,$password);
                if($result)
                {
                    ComHelper::retData();
                }
                ComHelper::retState(StateHelper::$ERROR_LOGIN);
            }
            ComHelper::retState(StateHelper::$ERROR_VERIFY);
        }
        ComHelper::retState(StateHelper::$ERROR_PARAM);
    }

    public function actionLoginOut()
    {
        UserService::self()->loginOut();
        return $this->redirect([\Yii::$app->homeUrl]);
    }

	/**接口文档登录
	 * @return string
	 * @author 姜海强 <jhq0113@163.com>
	 */
	public function actionRest()
	{
		return $this->render('auth');
	}

	/**接口文档
	 * @return string
	 */
	public function actionApp()
	{
		if($this->checkAuth())
		{
			return $this->renderPartial('app');
		}
		$this->redirect(['/login/auth']);
	}

	/**
	 * 鉴权
	 */
	public function actionAuth()
	{
		if(isset($_POST['auth']))
		{
			$auth=ComHelper::fStringP('auth');
			if($auth === \Yii::$app->params['appSecret'])
			{
				SNHelper::set(self::KEY,$this->createKey());
				ComHelper::retData();
			}
			ComHelper::retNullOrEmpty();
		}
		elseif(\Yii::$app->request->isGet)
		{
			return $this->render('auth');
		}
	}

	/**检查是否登录
	 * @return bool
	 */
	private function checkAuth()
	{
		$key=SNHelper::get(self::KEY);
		if($key === $this->createKey())
		{
			return true;
		}
		return false;
	}

	/**生成KEY
	 * @return string
	 */
	private function createKey()
	{
		return md5('dasadfdf@d'.md5(\Yii::$app->params['appSecret']).'d#asdadasdffas4');
	}
}