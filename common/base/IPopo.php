<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/25
 * Time: 14:31
 */

namespace common\base;


use yii\base\Configurable;
use yii\base\Model;

/**简单对象基类
 * Class IPopo
 * @package common\base
 * @author 姜海强 <jhq0113@163.com>
 */
class IPopo extends Model implements Configurable
{
    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    private $_attributes = [];

    /**
     * @param string $name
     * @return mixed|null
     * @author 姜海强 <jhq0113@163.com>
     */
    public function __get($name)
    {
        if (isset($this->_attributes[$name]) || array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @author 姜海强 <jhq0113@163.com>
     */
    public function __set($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed|null
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getAttribute($name)
    {
        return isset($this->_attributes[ $name ]) ? $this->_attributes[ $name ] : null;
    }

    /**
     * @param $name
     * @param $value
     * @author 姜海强 <jhq0113@163.com>
     */
    public function setAttribute($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    /**获取所有属性
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function attributes()
    {
        if(!empty($this->_attributes))
        {
            return array_keys($this->_attributes);
        }
        return [];
    }
}