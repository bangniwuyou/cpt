<?php
/**
 * 访问权限
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/11/28
 * Time: 19:26
 */

namespace backend\models\entity;


/**权限
 * Class AccessEntity
 * @package backend\models\entity
 * @author 姜海强 <jhq0113@163.com>
 */
class AccessEntity extends BaseEntity
{
    public $tableName='{{%admin_access}}';

    /**
     * @var array
     * @author 姜海强 <jhq0113@163.com>
     */
    public $fields=['role_id','right_id'];

    /**根据角色列表获取权限id列表
     * @param array    $roleIds    角色列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getRightListByRoleIds($roleIds)
    {
        if(is_array($roleIds) && !empty($roleIds))
        {
            foreach ($roleIds as &$value)
            {
                $value=(int)$value;
            }

            $result=$this->query(
                'SELECT '.$this->formatFields($this->fields).' FROM '.$this->getTableName().' WHERE `role_id` IN ('.implode(',',$roleIds).')',
                [],
                true
            );
            if(!empty($result))
            {
                return array_column($result,'rightId');
            }
        }
        return [];
    }

    /**给某角色清空权限
     * @param int   $roleId      角色ID
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function delAll($roleId)
    {
        return $this->execute(
            'DELETE FROM '.$this->getTableName().' WHERE `role_id`=:role_id',
            [
                ':role_id'=>$roleId
            ]
        );
    }

    /**增加权限
     * @param int   $roleId             角色ID
     * @param array $rightList          权限ID列表
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function addRights($roleId,$rightList)
    {
        if(!empty($rightList))
        {
            $values=[];
            foreach ($rightList as $right)
            {
                array_push($values,'('.(int)$roleId.','.(int)$right.')');
            }
            return $this->execute(
                'INSERT INTO '.$this->getTableName().'(`role_id`,`right_id`)VALUES'.implode(',',$values)
            );
        }
    }
}