<?php
/**
 * Created by PhpStorm.
 * User: 姜海强
 * Date: 2017/10/22
 * Email: <jhq0113@163.com>
 * Time: 12:21
 */

namespace file\controllers;


use common\base\IController;

/**
 * Class SiteController
 * @package file\controllers
 * @author  姜海强 <jhq0113@163.com>
 */
class SiteController extends IController
{
	public function actionIndex()
	{
		exit(json_encode(['status'=>404]));
	}
}