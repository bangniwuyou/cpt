<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/8
 * Time: 14:44
 */

/**
 * @param $class
 * @throws \yii\base\ErrorException
 * @author 姜海强 <jhq0113@163.com>
 */
function datetimePickerAutoload($class)
{
    $class = ltrim($class,'\\');
    if(strpos($class,'bootstrap_datetime_picker') === 0)
    {
        $class = str_replace('bootstrap_datetime_picker','',$class);
        $fileName = __DIR__.str_replace('\\','/',$class).'.php';
        if(file_exists($fileName)){
            require($fileName);
        }else{
            throw new \yii\base\ErrorException('未能找到'.$class);
        }
    }
}

/**
 * 类自动加载
 */
spl_autoload_register("datetimePickerAutoload");