<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/1
 * Time: 19:14
 */

namespace common\base;

/**
 * Class Redis
 * @package common\base
 * @author 姜海强 <jhq0113@163.com>
 */
abstract class IRedis extends IService
{
    /**主从Redis配置
     * 如：
    'redis'=>[
        'master'=>[
            'host'=>'192.168.1.17',
            'port'=>'6379',
            'password'=>'sadf#d2342fSDFQ#43dfdsf',
            'timeout'=>'3'
        ],
        'slaves'=>[
            [
            'host'=>'192.168.1.17',
            'port'=>'6379',
            'password'=>'sadf#d2342fSDFQ#43dfdsf',
            'timeout'=>'3'
            ],
            [
            'host'=>'192.168.1.17',
            'port'=>'6379',
            'password'=>'sadf#d2342fSDFQ#43dfdsf',
            'timeout'=>'3'
            ],
        ]
    ]
     * @var array redis配置
     */
    protected $config=null;

    /**\Redis连接池
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    private static $_redisPool=[];

    /**得到一个Redis实例
     * @param array|null $config     Redis配置
     * @param int $db                数据库
     * @return bool|\Redis
     * @author 姜海强 <jhq0113@163.com>
     */
    private function instance($config=null,$db=0)
    {
        if(isset($config['host'],$config['port'],$config['timeout']))
        {
            //不同的host port db 为一个连接
            $key=$config['host'].':'.$config['port'].':'.(string)$db;

            //池中没有
            if(!isset(self::$_redisPool[ $key ]))
            {
                $redis=new \Redis();
                if($redis->connect($config['host'],$config['port'],$config['timeout']))
                {
                    if(isset($config['password']) && !empty($config['password']))
                    {
                        $redis->auth($config['password']);
                    }
                    $redis->select($db);

                    //放入池中
                    self::$_redisPool[ $key ] = $redis;
                }
                else
                {
                    return false;
                }
            }

            return self::$_redisPool[ $key ];

        }
        return false;
    }

    /**选举从Redis算法
     * @param array $configs      从Redis配置列表
     * @return mixed
     * @author 姜海强 <jhq0113@163.com>
     */
    private function selectSlave(array $configs)
    {
        shuffle($configs);
        return $configs[0];
    }

    /**获取一个主Redis连接
     * @param int $db            数据库
     * @return false|\Redis
     * @author 姜海强 <jhq0113@163.com>
     */
    private function master($db=0)
    {
        $config=$this->config['master'];
        return $this->instance($config,$db);
    }

    /**获取一个从Redis连接
     * @param int $db               数据库
     * @return false|\Redis
     * @author 姜海强 <jhq0113@163.com>
     */
    private function slave($db=0)
    {
        $slaves=$this->config['slaves'];
        $config=$this->selectSlave($slaves);
        return $this->instance($config,$db);
    }

    /**得到一个\Redis实例
     * @param int $db              数据库
     * @param bool $userMaster     是否使用主，默认为从
     * @return bool|false|\Redis
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getRedis($db=0,$userMaster=false)
    {
        return $userMaster ? $this->master($db) : $this->slave($db);
    }
}