<?php
/**
 * Created by PhpStorm.
 * User: Qiang
 * Date: 2015/8/24
 * Time: 18:16
 */
namespace common\utils;

use common\base\IUtils;

/**SESSION操作帮助类
 * Class SNHelper
 * @package app\components
 */
class SNHelper extends IUtils
{
    /**得到Session组建
     * @return \yii\web\Session
     * @author 姜海强
     */
    private static function getSession()
    {
        return \Yii::$app->session;
    }

    /**获取SESSION
     * @param string $key                Key
     * @param mixed $defaultValue       Key
     * @return mixed
     */
    public static function get($key,$defaultValue=false)
    {
        return self::getSession()->get($key,$defaultValue);
    }

    /**设置SESSION
     * @param string $key           Key
     * @param mixed  $value         值
     */
    public static function set($key,$value)
    {
        return self::getSession()->set($key,$value);
    }

    /**清理SESSION
     * @param string $key      Key,不传会清空服务器SESSION
     */
    public static function del($key=null)
    {
        if(isset($key))
        {
            return self::getSession()->remove($key);
        }
        else
        {
            return self::getSession()->destroy();
        }
    }

    /**获取SessionID
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function getSessionId()
    {
        return self::getSession()->getId();
    }
}