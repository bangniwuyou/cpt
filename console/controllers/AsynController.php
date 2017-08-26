<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/25
 * Time: 14:35
 */

namespace console\controllers;


use console\models\popo\Task;

/**
 * Class AsynController
 * @package console\controllers
 * @author 姜海强 <jhq0113@163.com>
 */
class AsynController extends BaseController
{
    /**任务列表
     * @var
     * @author 姜海强 <jhq0113@163.com>
     */
    protected $tasks;

    /**
     * @author 姜海强 <jhq0113@163.com>
     */
    public function init()
    {
        parent::init();

        $tasks              =   \Yii::$app->params['tasks'];

        if(!empty($tasks))
        {
            foreach ($tasks as $task)
            {
                $task['class']=Task::class;
                $this->tasks [] = \Yii::createObject($task);
            }
        }
    }

    /**获取星期
     * @return  int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getWeek()
    {
        return (int)date('w',$this->time);
    }

    /**获取月
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getMonth()
    {
        return (int)date('m',$this->time);
    }

    /**获取日期
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getDay()
    {
        return (int)date('d',$this->time);
    }

    /**获取小时
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getHour()
    {
        return (int)date('H',$this->time);
    }

    /**获取分钟
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getMinute()
    {
        return (int)date('i',$this->time);
    }

    /**方法Map
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static $functionMap=[
        '0'=>'getMinute',
        '1'=>'getHour',
        '2'=>'getDay',
        '3'=>'getMonth',
        '4'=>'getWeek'
    ];

    /**校验是否等候
     * @param Task $task
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public function checkWait(Task $task)
    {
        if((!$task->appendExecute) && ($task->appendWaitTime >0))
        {
            $fileName = $task->getProcessFileNameByRoute( $task->route );
            if(file_exists($fileName))
            {
                $createTime=filemtime($fileName);
                if(($this->time - $createTime) < ( ((int)$task->appendWaitTime + 1) * 60) )
                {
                    return false;
                }
            }
        }
        return true;
    }

    /**检查 任务是否要执行
     * @param Task $task
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    protected function isExecute(Task $task)
    {
        $isCheck=$this->checkWait($task);
        if($isCheck)
        {
            list($minute,$hour,$day,$month,$week)=explode(' ',$task->pattern);
            if($this->checkExecute(4,$week))
            {
                if($this->checkExecute(3,$month))
                {
                    if($this->checkExecute(2,$day))
                    {
                        if($this->checkExecute(1,$hour))
                        {
                            return $this->checkExecute(0,$minute);
                        }
                    }
                }
            }
        }
        return false;
    }

    /**检查是否执行
     * @param $index
     * @param $timeItem
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public function checkExecute($index,$timeItem)
    {
        if($timeItem === '*')
        {
            return true;
        }

        $num = $this->{self::$functionMap[ $index ]}();
        if(mb_strpos($timeItem,',',null,'utf-8') !== false)
        {
            $items=explode(',',$timeItem);
            return in_array($num,$items);
        }

        if(mb_strpos($timeItem,'-',null,'utf-8') !== false)
        {
            list($start,$end)=explode('-',$timeItem);
            return ((int)$start <= $num) && ((int)$end >= $num);
        }

        if(mb_strpos($timeItem,'/',null,'utf-8') !==false)
        {
            list($random,$per)=explode('/',$timeItem);
            return ($num % (int)$per) === 0;
        }

        return $timeItem == (int)$num;
    }

    /**执行任务
     * @param Task $task   任务实例
     * @author 姜海强 <jhq0113@163.com>
     */
    protected function executeTask(Task $task)
    {
        $executePrefix=PHP.' '.\Yii::getAlias('@console').'/web/yii.php ';
        $timeout='timeout '.$this->timeLimit.' ';
        $file=popen($timeout.$executePrefix.$task->route.' >/dev/null 2>&1 &', 'r');
        if($file)
        {
            pclose($file);
        }
    }

    /**执行任务
     * @author 姜海强 <jhq0113@163.com>
     */
    public function actionRun()
    {
        if(isset($this->tasks))
        {
            foreach ($this->tasks as $task)
            {
                if($this->isExecute($task))
                {
                    $this->executeTask($task);
                }
            }
        }
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public function beforeAction($action)
    {
        $result=parent::beforeAction($action); // TODO: Change the autogenerated stub

        //记录日志
        $this->writeLog('   route:'.$this->id.'/'.$action->id.'     --开始执行');

        return $result;
    }

    /**
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return mixed
     * @author 姜海强 <jhq0113@163.com>
     */
    public function afterAction($action, $result)
    {
        $this->writeLog('   route:'.$this->id.'/'.$action->id.'     --执行完毕');

        exit();
    }
}