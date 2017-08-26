<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/1
 * Time: 19:37
 */

namespace common\base;

/**
 * Class IUtils
 * @package common\base
 * @author 姜海强 <jhq0113@163.com>
 */
abstract class IUtils
{
    /**子类不允许被实例化
     * IUtils constructor.
     */
    private function __construct()
    {

    }

    /**子类不允许被克隆
     * @author 姜海强 <jhq0113@163.com>
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
}