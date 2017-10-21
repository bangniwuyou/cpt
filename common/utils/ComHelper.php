<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/2
 * Time: 17:53
 */

namespace common\utils;


use common\base\IUtils;

/**
 * Class ComHelper
 * @package common\utils
 * @author 姜海强 <jhq0113@163.com>
 */
class ComHelper extends IUtils
{
    //------------------时间相关----------------

    //半分钟
    const HALF_MINUTE=30;
    //一分钟
    const ONE_MINUTE=60;
    //十分钟
    const TEN_MINUTE=600;
    //三十分钟
    const THIRTY_MINUTE=1800;
    //一小时
    const ONE_HOUR=3600;
    //一天
    const ONE_DAY=86400;
    //七天
    const SEVEN_DAY=604800;

    //------------------时间相关----------------

    /**下划线分割转驼峰
     * @param string $str             字符串
     * @param bool   $ucFirst         true为大驼峰，false小驼峰
     * @return mixed|string
     */
    public static function line2TuoFeng($str,$ucFirst=false)
    {
        $str = ucwords(str_replace('_', ' ', $str));
        $str = str_replace(' ','',lcfirst($str));
        return $ucFirst ? ucfirst($str) : $str;
    }

    /**中线分割转驼峰
     * @param string $str             字符串
     * @param bool   $ucFirst         true为大驼峰，false小驼峰
     * @return mixed|string
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function middleLine2TuoFeng($str,$ucFirst=false)
    {
        $str = ucwords(str_replace('-', ' ', $str));
        $str = str_replace(' ','',lcfirst($str));
        return $ucFirst ? ucfirst($str) : $str;
    }

    /**小驼峰变下划线
     * @param string $str
     * @return mixed
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function tuoFeng2Line($str)
    {
        $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
            return '_'.strtolower($matches[0]);
        },$str);
        return ltrim($str,'_');
    }

    //--------------------------------获取参数--------------------------------------
    /**得到Request客户端IP地址
     * @return string
     */
    public static function getClientIP()
    {
        $ip = '';
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
            return $ip[0];
        }
        elseif (!empty($_SERVER['REMOTE_ADDR']))
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**得到long形式的客户端ip
     * @return int  int形式ip地址
     */
    public static function getClientLongIp()
    {
        return ip2long(self::getClientIP());
    }

    /**
     * string 类型
     */
    const STRING = 'string';
    /**
     * int类型
     */
    const INT = 'int';
    /**
     * float类型
     */
    const FLOAT = 'float';

    /**参数过滤
     * @param string $type 转换类型
     * @param string $val                  值，如：$_REQUEST['userName'],$_POST['passWord'],$_GET['redirectUrl']
     * @return float|int|string     过滤后的值
     */
    public static function filterParam($type = 'string', $val = '')
    {
        if (isset($val))
        {
            $type = strtolower($type);
            switch ($type)
            {
                case self::STRING:
                    return addslashes(trim($val));
                case self::INT:
                    return (int)trim($val);
                case self::FLOAT:
                    return (float)trim($val);
                default:
                    return addslashes(trim($val));
            }
        }
        return false;
    }

    /**获取STRING类型数据
     * @param string $key
     * @return string
     */
    public static function fString($key)
    {
        return (isset($_REQUEST[$key]) && !empty(trim($_REQUEST[$key]))) ? self::filterParam(self::STRING, $_REQUEST[$key]) : '';
    }

    /**获取INT类型数据
     * @param string $key
     * @return int
     */
    public static function fInt($key)
    {
        return isset($_REQUEST[$key]) ? self::filterParam(self::INT, $_REQUEST[$key]) : 0;
    }

    /**获取FLOAT类型数据
     * @param string $key
     * @return float
     */
    public static function fFloat($key)
    {
        return isset($_REQUEST[$key]) ? self::filterParam(self::FLOAT, $_REQUEST[$key]) : 0;
    }

    /**获取POST STRING类型数据
     * @param string $key
     * @return float|int|string
     */
    public static function fStringP($key)
    {
        return (isset($_POST[$key]) && !empty(trim($_POST[$key]))) ? self::filterParam(self::STRING, $_POST[$key]) : '';
    }

    /**获取POST INT类型数据
     * @param string $key
     * @return float|int|string
     */
    public static function fIntP($key)
    {
        return isset($_POST[$key]) ? self::filterParam(self::INT, $_POST[$key]) : 0;
    }

    /**获取POST Float类型数据
     * @param string $key
     * @return float|int|string
     */
    public static function fFloatP($key)
    {
        return isset($_POST[$key]) ? self::filterParam(self::FLOAT, $_POST[$key]) : 0;
    }

    /**获取GET STRING类型数据
     * @param string $key
     * @return float|int|string
     */
    public static function fStringG($key)
    {
        return (isset($_GET[$key]) && !empty(trim($_GET[$key]))) ? self::filterParam(self::STRING, $_GET[$key]) : '';
    }

    /**获取GET Int类型数据
     * @param string $key
     * @return float|int|string
     */
    public static function fIntG($key)
    {
        return isset($_GET[$key]) ? self::filterParam(self::INT, $_GET[$key]) : 0;
    }

    /**获取GET Float类型数据
     * @param string $key
     * @return float|int|string
     */
    public static function fFloatG($key)
    {
        return isset($_GET[$key]) ? self::filterParam(self::FLOAT, $_GET[$key]) : 0;
    }

    //--------------------------------获取参数--------------------------------------



    //--------------------------------返回数据--------------------------------------
    /**检测是否是跨域请求
     * @return bool
     * @author 姜海强
     */
    private static function checkCrossDomain()
    {
        return self::fIntP('cdRoMani') == 1;
    }

    /**得到跨域脚本
     * @return string
     * @author 姜海强
     */
    private static function getRetData($result)
    {
        return (self::checkCrossDomain()?CROSSDOMAINSCRIPT:'').json_encode($result);
    }

    /**返回状态信息，具体参考StateHelper
     * @param array $state         StateHelper的静态属性
     * @param mixed $addtion       附加信息
     */
    public static function retState(array $state,$addtion='')
    {
        $result = ['data'=> $state['data'],'status' => $state['status'],'addition'=>$addtion];
        self::retArray($result);
    }

    /**通用Ajax返回信息    SUCCESS
     * @param mixed $data                DATA
     * @param mixed $status              状态码
     */
    public static function retData($data = 'SUCCESS', $status = '200')
    {
        $result=['status' => $status,'data'=> $data];
        self::retArray($result);
    }

    /**返回数据
     * @param array $data
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function retArray(array $data)
    {
        \Yii::$app->response->send();
        exit(self::getRetData($data));
    }

    /**
     *通用Ajax返回信息   NULL_OR_EMPTY
     */
    public static function retNullOrEmpty($data = "")
    {
        $result = ['status' => '-1','data' => $data];
        \Yii::$app->response->send();
        exit(self::getRetData($result));
    }
    //--------------------------------返回数据--------------------------------------

    /**
     * 生成随机数
     * @param int    $l  长度
     * @param string $c  种子
     * @return string
     */
    public static function createRandstr($l, $c = '123456789')
    {
        $s='';
        $cl=strlen($c) - 1;
        for ($i = 0; $i < $l;  ++$i)
        {
            $s .= $c[ mt_rand(0, $cl) ];
        }
        return $s;
    }

    /**秒转分钟
     * @param int   $seconds    秒数
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function second2Minute($seconds)
    {
        $minute  = floor($seconds / 60);
        $seconds = $seconds - $minute * 60;
        $minute  = ($minute <10)  ? '0'.(string)$minute : $minute;
        $seconds = ($seconds <10) ? '0'.(string)$minute : $seconds;
        return $minute.':'.$seconds;
    }
}