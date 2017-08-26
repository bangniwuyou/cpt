<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/3/22
 * Time: 19:23
 */

namespace backend\components;

/**
 * Class Session
 * @package backend\components
 * @author 姜海强 <jhq0113@163.com>
 */
class Session extends \yii\web\Session
{
    /**获取过期时间
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getTimeout()
    {
        return 86400;
    }
}