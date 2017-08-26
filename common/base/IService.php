<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/1
 * Time: 19:15
 */

namespace common\base;

/**
 * Class IService
 * @package common\base
 * @author 姜海强 <jhq0113@163.com>
 */
abstract class IService
{
    /**对象池
     * @var
     * @author 姜海强 <jhq0113@163.com>
     */
    private static $_instance = [];

    /**
     * @author 姜海强 <jhq0113@163.com>
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**统一实例化方法
     * @return static
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function self()
    {
        $className=get_called_class();
        if(!isset(self::$_instance[ $className ]))
        {
            self::$_instance[ $className ] = new $className;
        }
        return self::$_instance [ $className ];
    }
}