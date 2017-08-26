<?php
/**
 * 用户角色表
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/11/28
 * Time: 19:16
 */

namespace backend\models\entity;

/**用户角色表
 * Class UserRoleEntity
 * @package backend\models\entity
 * @author 姜海强 <jhq0113@163.com>
 */
class UserRoleEntity extends BaseEntity
{
    public $tableName='{{%admin_user_role}}';

    public $fields=[
        'admin_id',
        'role_id'
    ];

    /**根据后台管理员id获取角色列表
     * @param int          $userId            用户id
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getRoleListByUserId($userId)
    {
        $list=$this->query(
            'SELECT `role_id` AS roleId FROM '.$this->getTableName().' WHERE `admin_id`=:admin_id',
            [
                ':admin_id'=>$userId
            ],
            true
        );
        if(!empty($list))
        {
            return array_column($list,'roleId');
        }
        return [];
    }

    /**清空用户角色
     * @param int    $userId     用户id
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function delAllByUserId($userId)
    {
        return $this->execute(
            'DELETE FROM '.$this->getTableName().' WHERE `admin_id`=:admin_id',
            [
                ':admin_id'=>$userId
            ]
        );
    }

    /**给用户增加角色
     * @param int    $userId       用户ID
     * @param array  $roleList     角色id列表
     * @author 姜海强 <jhq0113@163.com>
     */
    public function addRoles($userId,$roleList)
    {
        if(!empty($roleList))
        {
            $values=[];
            foreach ($roleList as $roleId)
            {
                array_push($values,'('.(int)$userId.','.(int)$roleId.')');
            }
            $this->execute(
                'INSERT INTO '.$this->getTableName().'(`admin_id`,`role_id`)VALUES'.implode(',',$values)
            );
        }
    }
}