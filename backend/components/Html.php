<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/12/2
 * Time: 16:39
 */

namespace backend\components;

/**
 * Class Html
 * @package backend\components
 * @author 姜海强 <jhq0113@163.com>
 */
class Html extends \yii\helpers\Html
{
    /**格式化后台Url
     * @param $url
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function formatUrl($url)
    {
        return mb_substr($url,0,4) === 'http' ? $url : FILE_URL.$url;
    }

    /**通用设置按钮
     * @param string   $url       url地址
     * @param int      $value     值
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function dealAButton($url,$value,$enableValue='启用',$disableValue='禁用')
    {
        if($value ==1)
        {
            $class='btn-danger';
            $url=$url.'&status=0';
            $msg=$disableValue;
            $status='<span class="label" style="color: #5cb85c;">已'.$enableValue.'</span>';
        }
        else{
            $class='btn-success';
            $url=$url.'&status=1';
            $msg=$enableValue;
            $status='<span class="label" style="color: #ff0000;">已'.$disableValue.'</span>';
        }
        return $status.'<a href="'.$url.'" class="btn '.$class.'">'.$msg.'</a>';
    }

    /**小图
     * @param string $url
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function smallImg($url='')
    {
        return '<img src="'.self::formatUrl($url).'" class="ke-small-img" />';
    }
}