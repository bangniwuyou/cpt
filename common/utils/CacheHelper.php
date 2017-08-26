<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/24
 * Time: 10:25
 */

namespace common\utils;


use common\base\IRedis;
use common\base\IUtils;

class CacheHelper extends IUtils
{
    //------------------------------缓存时间配置------------------------------
    /**
     * 永久缓存
     */
    const TIMEOUT_FOREVER=0;

    /**
     * 十秒
     */
    const TIMEOUT_TEN_SECONDS=10;

    /**
     * 一分钟
     */
    const TIMEOUT_ONE_MINUTE=60;

    /**
     * 十分钟
     */
    const TIMEOUT_TEN_MINUTES=600;

    /**
     * 半小时
     */
    const TIMEOUT_HALF_HOUR=1800;

    /**
     * 一小时
     */
    const TIMEOUT_ONE_HOUR=3600;

    /**
     * 半天
     */
    const TIMEOUT_HALF_DAY=43200;

    /**
     * 一天
     */
    const TIMEOUT_ONE_DAY=86400;

    //------------------------------缓存时间配置------------------------------

    /**
     * 计算锁后缀
     */
    const LOCK_WAVE_SUFFIX='_countLock';

    /**
     * 数据库为空
     */
    const DB_EMPTY='-1';

    /**
     * 波式缓存倍数
     */
    const WAVE_CACHE_TIMES=2;

    /**加波式缓存锁
     * @param IRedis    $redis           IRedis实例
     * @param string    $redisKey        RedisKey
     * @param int       $expireAt        过期时间戳
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    private static function _lockWaveCache(IRedis $redis,$redisKey,$expireAt)
    {
        $timeSpan   = $expireAt - time();
        //如果缓存失效
        if($timeSpan < 1)
        {
            $writeRedis = $redis->getRedis(0,true);
            $result =   $writeRedis->incr($redisKey.self::LOCK_WAVE_SUFFIX);

            //第一个发现缓存失效
            if($result  === 1)
            {
                return true;
            }

            //如果缓存超时10秒，并且已经超过100个访问发现缓存失效，认为锁失效,同样获得锁
            return ($timeSpan < -9) && ($result >100);
        }
    }

    /**删除波式缓存锁
     * @param \Redis    $redis      \Redis实例
     * @param string    $redisKey   RedisKey
     * @author 姜海强 <jhq0113@163.com>
     */
    private static function _delLock(\Redis $redis,$redisKey)
    {
        $redis->del($redisKey.self::LOCK_WAVE_SUFFIX);
    }

    /**波式缓存是否可用
     * @param array     $cacheData   波式缓存取到的数据
     * @param IRedis    $redis       IRedis实例
     * @param string    $redisKey    RedisKey
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    private static function _isCacheable($cacheData,IRedis $redis,$redisKey)
    {
        if(isset($cacheData['data'],$cacheData['expireAt']))
        {
            //如果锁住，即第一个没有命中缓存或锁已失效
            if(self::_lockWaveCache($redis,$redisKey,$cacheData['expireAt']))
            {
                return false;
            }

            return $cacheData['data'];
        }

        return false;
    }

    /**波式缓存数据
     * @param \Redis    $redis        redis实例
     * @param string    $redisKey     缓存Key
     * @param array     $data         数据
     * @param int       $timeout      超时
     * @author 姜海强 <jhq0113@163.com>
     */
    private static function _waveCacheData(\Redis $redis,$redisKey,$data,$timeout)
    {
        $serialize['data']=$data;

        //数据库为空
        if(empty($data))
        {
            $serialize['data']=self::DB_EMPTY;
        }

        //永久缓存
        if( (int)$timeout === self::TIMEOUT_FOREVER )
        {
            $serialize['expireAt']='0';
            $redis->set($redisKey,serialize($serialize));
        }
        else   //非永久缓存
        {
            $realTimeout=self::WAVE_CACHE_TIMES * $timeout;
            $serialize['expireAt']=time() + $timeout;

            $redis->set($redisKey,serialize($serialize),$realTimeout);
        }
    }

    /**使用波式缓存
     * @param callable  $func         从数据库取数据逻辑function
     * @param string    $redisKey     存储数据的RedisKey
     * @param IRedis    $redis        IRedis实例
     * @param int       $timeout      存储时间，默认为1分钟
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function waveCache(callable $func,$redisKey,IRedis $redis,$timeout=self::TIMEOUT_ONE_MINUTE,$useMaster=false)
    {
        $data=[];

        $readRedis = $redis->getRedis(0,$useMaster);

        //读取波式缓存
        $cacheData=$readRedis->get($redisKey);

        if(!empty($cacheData))
        {
            $cacheData  = unserialize($cacheData);

            $result     = self::_isCacheable($cacheData,$redis,$redisKey);

            //如果数据库也为空
            if($result == self::DB_EMPTY)
            {
                return $data;
            }

            //如果有数据
            if(!empty($result))
            {
                return $result;
            }

            //如果为第一个访问到缓存失效
        }

        //如果用主
        if($useMaster)
        {
            $writeRedis = $readRedis;
        }
        else  //如果用从
        {
            $writeRedis = $redis->getRedis(0,true);
        }

        //从数据库取数据
        $data=call_user_func($func);

        //将数据放入波式缓存
        self::_waveCacheData($writeRedis,$redisKey,$data,$timeout);

        //删除访问者限制
        self::_delLock($writeRedis,$redisKey);


        return $data;
    }

    /**删除波式缓存
     * @param IRedis    $redis              IRedis实例
     * @param string    $redisKey           RedisKey
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function delWaveCache(IRedis $redis,$redisKey)
    {
        $writeRedis = $redis->getRedis(0,true);
        if($writeRedis)
        {
            $cacheData=$writeRedis->get($redisKey);
            if(!empty($cacheData))
            {
                $cacheData = unserialize($cacheData);
                if(isset($cacheData['expireAt'],$cacheData['data']))
                {
                    $cacheData['expireAt']=time()-1;
                    $writeRedis->set($redisKey,serialize($cacheData),self::TIMEOUT_ONE_MINUTE);
                }
            }
        }
    }
}