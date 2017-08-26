<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/25
 * Time: 17:04
 */

namespace www\utils;


use common\base\IUtils;

/**静态资源版本控制
 * Class VersionHelper
 * @package web\utils
 * @author 姜海强 <jhq0113@163.com>
 */
class VersionHelper extends IUtils
{
    /**
     * 版本
     */
    const VERSION = '?version=082501';

    /**注册js文件
     * @param string $url
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function Js($url)
    {
        \Yii::$app->view->registerJsFile($url.self::VERSION);
    }

    /**注册css文件
     * @param string  $url
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function Css($url)
    {
        \Yii::$app->view->registerCssFile($url.self::VERSION);
    }
}