<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/19
 * Time: 14:16
 */

namespace console\models\popo;

use common\base\IPopo;

/**
 * Class Task
 * @package console\models
 * @author 姜海强 <jhq0113@163.com>
 */
class Task extends IPopo
{
    /**crontab时间表达式
     * @var
     * @author 姜海强 <jhq0113@163.com>
     */
    public $pattern;

    /**路由
     * @var
     * @author 姜海强 <jhq0113@163.com>
     */
    public $route;

    /**上一次任务没有执行完毕是否追加实行
     * @var bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public $appendExecute = false;

    /**分钟
     * @var int
     * @author 姜海强 <jhq0113@163.com>
     */
    public $appendWaitTime = 1;

    /**根据路由获取任务进程文件名称
     * @param string   $route   路由
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getProcessFileNameByRoute($route)
    {
        $path = \Yii::getAlias('@console').'/runtime/process/';
        if(!file_exists($path))
        {
            mkdir($path);
        }
        $routes =   explode('/',$route);
        return $path.$routes[0].'-'.$routes[1].'.pid';
    }

    /**创建任务进程文件
     * @author 姜海强 <jhq0113@163.com>
     */
    public function writeProcessId()
    {
        $fileName=$this->getProcessFileNameByRoute($this->route);
        $file=fopen($fileName,'w');
        if($file)
        {
            fwrite($file,$fileName.uniqid());
            fclose($file);
        }
    }

    /**删除任务进程文件
     * @author 姜海强 <jhq0113@163.com>
     */
    public function deleteProcessId()
    {
        $fileName=$this->getProcessFileNameByRoute($this->route);
        if(file_exists($fileName))
        {
            @unlink($fileName);
        }
    }
}