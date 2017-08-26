<?php
/**
 * 访问权限控制服务
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/12/3
 * Time: 14:02
 */

namespace backend\service;


use backend\models\entity\AccessEntity;

class AccessService extends BaseService
{
    public function __construct()
    {
        $this->entity=new AccessEntity();
    }

    /**根据角色列表获取权限列表
     * @param array   $roleIds     角色列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getRightListByRoleIds($roleIds)
    {
        return $this->entity->getRightListByRoleIds($roleIds);
    }

    /**修改权限
     * @param int   $roleId             角色ID
     * @param array $rightList          权限列表
     * @author 姜海强 <jhq0113@163.com>
     */
    public function save($roleId,$rightList)
    {
        $this->entity->delAll($roleId);
        if(!empty($rightList))
        {
            $rightList=array_unique($rightList);
            $this->entity->addRights($roleId,$rightList);
        }
    }
}