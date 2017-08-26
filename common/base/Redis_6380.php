<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/24
 * Time: 21:57
 */

namespace common\base;

/**6380实例，以此类推，如果需要很多实例，端口不要重复，以便维护和沟通
 * Class Redis_6380
 * @package common\base
 * @author 姜海强 <jhq0113@163.com>
 */
class Redis_6380 extends IRedis
{
    /**
     * Redis_6380 constructor.
     */
    public function __construct()
    {
        $this->config   = \Yii::$app->params['redisConfig']['6380'];
    }
}