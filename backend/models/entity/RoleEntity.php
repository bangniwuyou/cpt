<?php
/**
 * 角色
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/12/3
 * Time: 10:57
 */

namespace backend\models\entity;


/**角色
 * Class RoleEntity
 * @package backend\models\entity
 * @author 姜海强 <jhq0113@163.com>
 */
class RoleEntity extends BaseEntity
{
    public $tableName='{{%admin_role}}';

    public $fields=[
        'id',
        'role_name',
        'is_on',
        'add_time',
        'update_time'
    ];

    /**添加角色
     * @param array   $item   角色元素
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function add($item)
    {
        return $this->execute(
            'INSERT INTO '.$this->getTableName().'(`role_name`,`is_on`,`add_time`)VALUES(:role_name,:is_on,\''.date('Y-m-d H:i:s').'\')',
            [
                ':role_name'=>$item['roleName'],
                ':is_on'=>$item['isOn']
            ]
        );
    }

    /**更新角色
     * @param array   $item     角色元素
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function update($item)
    {
        return $this->execute(
            'UPDATE '.$this->getTableName().' SET `role_name`=:role_name,`is_on`=:is_on WHERE `id`=:id LIMIT 1',
            [
                ':role_name'=>$item['roleName'],
                ':is_on'=>$item['isOn'],
                ':id'=>$item['id']
            ]
        );
    }

    /**检查角色名称是否已经存在
     * @param string    $roleName      角色名称
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public function checkExists($roleName)
    {
        $role=$this->queryOne(
            'SELECT `id` FROM '.$this->getTableName().' WHERE `role_name`=:role_name',
            [
                ':role_name'=>$roleName
            ],
            true
        );
        if(!empty($role))
        {
            return true;
        }
        return false;
    }

    /**根据角色列表获取可用的角色
     * @param array  $roleIds   角色id列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getEnableRolesListByRoleIds($roleIds)
    {
        $enableRoleIds=[];
        if(!empty($roleIds))
        {
            $enableRoleIds=$this->query('SELECT `id` FROM '.$this->getTableName().' WHERE `id` IN('.implode(',',$roleIds).') AND `is_on`=1');
            if(!empty($enableRoleIds))
            {
                $enableRoleIds=array_column($enableRoleIds,'id');
            }
        }
        return $enableRoleIds;
    }

    /**检查角色是否可以更新
     * @param string    $roleName    角色名称
     * @param int       $id          角色id
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public function checkEnableUpdate($roleName,$id)
    {
        $role=$this->queryOne(
            'SELECT `id` FROM '.$this->getTableName().' WHERE `role_name`=:role_name',
            [
                ':role_name'=>$roleName
            ],
            true
        );
        if(!empty($role))
        {
            return $role['id'] == $id;
        }
        return true;
    }
}