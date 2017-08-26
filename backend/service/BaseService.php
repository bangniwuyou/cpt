<?php
/**
 * 后台基础服务
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/11/28
 * Time: 18:47
 */

namespace backend\service;

use backend\models\ar\AdminOperateLog;
use backend\models\entity\BaseEntity;
use common\base\IService;
use common\utils\ComHelper;
use yii\caching\Cache;

class BaseService extends IService
{
    //region 通用实体数据处理
    /**默认绑定实体
     * @var  BaseEntity
     * @author 姜海强 <jhq0113@163.com>
     */
    protected $entity;

    /**获取所有列表
     * @return mixed
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getAll()
    {
        return $this->entity->getAll();
    }

    /**获取所有可用列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getAllEnableList()
    {
        return $this->entity->getAllEnableList();
    }

    /**根据ID获取信息详情
     * @param int    $id      id
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getInfo($id)
    {
        return $this->entity->getInfo($id);
    }

    /**根据ID删除
     * @param int    $id     id
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function delById($id)
    {
        return $this->entity->del($id);
    }

    /**启用状态设置
     * @param int   $id             id
     * @param int   $status         状态值
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function on($id,$status)
    {
        return $this->entity->on($id,$status);
    }
    //endregion

    //region 通用缓存处理
    /**
     * @return Cache
     * @author 姜海强 <jhq0113@163.com>
     */
    private static function getCache()
    {
        return \Yii::$app->cache;
    }

    /**设置文件缓存
     * @param $key
     * @param $value
     * @param int $expire
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function set($key,$value,$expire=0)
    {
        return self::getCache()->set($key,$value,$expire);
    }

    /**得到文件缓存
     * @param $key
     * @return mixed
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function get($key)
    {
        return self::getCache()->get($key);
    }

    /**检查Key是否存在
     * @param $key
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function exists($key)
    {
        return self::getCache()->exists($key);
    }

    /**删除文件缓存
     * @param $key
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function del($key)
    {
        return self::getCache()->delete($key);
    }
    //endregion

    /**添加操作日志
     * @param array  $userInfo      登陆管理员信息
     * @param int    $operateId     操作ID
     * @param string $desc          操作描述
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function addOperateLog($userInfo,$operateId,$desc='')
    {
        $operateLog=(new AdminOperateLog());
        $operateLog->setAttribute('admin_id',$userInfo['id']);
        $operateLog->setAttribute('admin_name',$userInfo['trueName']);
        $operateLog->setAttribute('operate_id',$operateId);
        $operateLog->setAttribute('operate_desc',$desc);
        $operateLog->setAttribute('operate_ip',ComHelper::getClientLongIp());
        $operateLog->save();
    }
}