<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/25
 * Time: 16:21
 */

namespace common\service;


use common\base\IDb;
use common\base\IRedis;
use common\base\IService;

class Service extends IService
{
    /**
     * @var IDb  数据访问实体
     * @author 姜海强 <jhq0113@163.com>
     */
    protected $entity;

    /**
     * @var IRedis  Redis访问实体
     * @author 姜海强 <jhq0113@163.com>
     */
    protected $redis;

}