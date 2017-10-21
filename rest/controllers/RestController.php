<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/20
 * Time: 14:56
 */

namespace rest\controllers;


use common\base\IController;
use common\utils\ComHelper;

/**
 * Class RestController
 * @package rest\controllers
 * @author 姜海强 <jhq0113@163.com>
 */
class RestController extends IController
{
    /**禁用csrf
     * @var bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public $enableCsrfValidation = false;

    /**
     * @var string Service类名
     * @author 姜海强 <jhq0113@163.com>
     */
    public $serviceClass;

    /**
     * @author 姜海强 <jhq0113@163.com>
     */
    public function init()
    {
        parent::init();
        /**
         * 根据控制器名称自动状态Service类
         */
        if(empty($this->serviceClass))
        {
            $this->serviceClass = 'rest\service\\'.ComHelper::middleLine2TuoFeng($this->id,true);
        }
    }
}