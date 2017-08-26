<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/25
 * Time: 16:02
 */

namespace rest\modules\v1\actions\user;


use rest\modules\v1\actions\V1Action;

/**
 * Class LoginAction
 * @package rest\modules\v1\actions\user
 * @author 姜海强 <jhq0113@163.com>
 */
class LoginAction extends V1Action
{
    /**
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function run()
    {
        return ['status'=>200,'message'=>'登录成功','data'=>[]];
    }
}