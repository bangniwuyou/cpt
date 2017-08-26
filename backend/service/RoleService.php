<?php
/**
 * 角色
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/12/3
 * Time: 11:16
 */

namespace backend\service;


use backend\components\StateHelper;
use backend\models\entity\RoleEntity;
use common\utils\ComHelper;

class RoleService extends BaseService
{
    /**
     * RoleService constructor.
     */
    public function __construct()
    {
        $this->entity=new RoleEntity();
    }

    /**插入或者更新
     * @param array   $item    角色元素
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function save($item)
    {
        if(isset($item['id']) && !empty($item['id']))
        {
            if($this->entity->checkEnableUpdate($item['roleName'],$item['id']))
            {
                return $this->entity->update($item);
            }
            ComHelper::retState(StateHelper::$ERROR_ROLE_EXISTS);
        }
        if($this->entity->checkExists($item['roleName']))
        {
            ComHelper::retState(StateHelper::$ERROR_ROLE_EXISTS);
        }
        return $this->entity->add($item);
    }
}