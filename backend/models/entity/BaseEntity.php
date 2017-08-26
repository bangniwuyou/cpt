<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/8/25
 * Time: 18:16
 */

namespace backend\models\entity;


use common\base\Db;

/**后台基础操作
 * Class BaseEntity
 * @package backend\models\entity
 * @author 姜海强 <jhq0113@163.com>
 */
class BaseEntity extends Db
{
    /**得到所有角色列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getAll()
    {
        return $this->query(
            'SELECT '.$this->formatFields($this->fields).' FROM '.$this->tableName,
            [],
            true
        );
    }

    /**得到所有可用列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getAllEnableList()
    {
        return $this->query(
            'SELECT '.$this->formatFields($this->fields).' FROM '.$this->tableName.' WHERE `is_on`=1 ',
            [],
            true
        );
    }

    /**删除
     * @param int   $id     ID
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function del($id)
    {
        return $this->execute(
            'DELETE FROM '.$this->tableName.' WHERE `id`=:id LIMIT 1',
            [
                ':id'=>(int)$id
            ]
        );
    }

    /**设置是否启用
     * @param int $id          id
     * @param int $status      状态码
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function on($id,$status)
    {
        return $this->execute(
            'UPDATE '.$this->tableName.' SET `is_on`=:is_on WHERE `id`=:id LIMIT 1',
            [
                ':id'=>$id,
                ':is_on'=>$status
            ]
        );
    }
}