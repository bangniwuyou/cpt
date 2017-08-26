<?php
/**
 * 菜单服务
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/11/28
 * Time: 18:47
 */

namespace backend\service;

use backend\components\FileCacheKey;
use backend\models\entity\AccessEntity;
use backend\models\entity\AdminUserEntity;
use backend\models\entity\RightEntity;
use backend\models\entity\RoleEntity;
use backend\models\entity\UserRoleEntity;
use common\utils\ComHelper;
use yii\helpers\ArrayHelper;

class MenuService extends BaseService
{
    /**后台用户表
     * @var AdminUserEntity
     * @author 姜海强 <jhq0113@163.com>
     */
    private $adminUserEntity;

    /**用户角色表
     * @var UserRoleEntity
     * @author 姜海强 <jhq0113@163.com>
     */
    private $userRoleEntity;

    /**角色权限表
     * @var AccessEntity
     * @author 姜海强 <jhq0113@163.com>
     */
    private $accessEntity;

    /**权限表
     * @var RightEntity
     * @author 姜海强 <jhq0113@163.com>
     */
    private $rightEntity;

    /**角色表
     * @var RoleEntity
     * @author 姜海强 <jhq0113@163.com>
     */
    private $roleEntity;

    public function __construct()
    {
        $this->adminUserEntity=new AdminUserEntity();
        $this->userRoleEntity=new UserRoleEntity();
        $this->accessEntity=new AccessEntity();
        $this->rightEntity=new RightEntity();
        $this->roleEntity=new RoleEntity();
    }

    /**清除所有权限菜单缓存
     * @author 姜海强 <jhq0113@163.com>
     */
    public function delAllAdminMenu()
    {
        $userList=$this->adminUserEntity->getAll();
        if(!empty($userList))
        {
            foreach ($userList as $value)
            {
                $this->delMenuByAdminId($value['id']);
            }
        }
    }

    /**根据后台用户ID清除某人的权限菜单
     * @param int    $adminId      后台用户ID
     * @author 姜海强 <jhq0113@163.com>
     */
    public function delMenuByAdminId($adminId)
    {
        $key=FileCacheKey::ADMIN_MENU_PREFIX.$adminId;
        if(self::exists($key))
        {
            self::del($key);
        }
    }

    /**根据后台用户ID获取菜单
     * @param int     $adminId    后台用户ID
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getMenuByAdminId($adminId)
    {
        $menu=self::get(FileCacheKey::ADMIN_MENU_PREFIX.$adminId);
        if(!empty($menu))
        {
            return $menu;
        }

        if(empty($menu))
        {
            $info=$this->adminUserEntity->getEnableInfoById($adminId);
            if(!empty($info))
            {
                $rightList=[];
                if($info['isSuperAdmin'] == 1)  //超级管理员
                {
                    $rightList=$this->rightEntity->getAllEnableList();
                }
                else
                {
                    $roleList=$this->userRoleEntity->getRoleListByUserId($adminId);
                    //获取可用角色
                    $roleList=$this->roleEntity->getEnableRolesListByRoleIds($roleList);
                    if(!empty($roleList))
                    {
                        $rightIds=$this->accessEntity->getRightListByRoleIds($roleList);
                        if(!empty($rightIds))
                        {
                            $rightList=$this->rightEntity->getEnableListByIds($rightIds);
                        }
                    }
                }
                $menu=self::rightListToMenu($rightList);

                if(!empty($menu))
                {
                    self::set(FileCacheKey::ADMIN_MENU_PREFIX.$adminId,$menu,ComHelper::ONE_HOUR);
                }

                return $menu;
            }
        }
        return [];
    }

    /**由权限得到菜单列表
     * @param array    $rightList          权限列表
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public static function rightListToMenu($rightList)
    {
        if(!empty($rightList))
        {
            $rightList=ArrayHelper::index($rightList,'id');
            $menu=[];
            foreach ($rightList as $value)
            {
                if($value['level'] == RightEntity::MODULE)
                {
                    $menu[$value['id']]=$value;
                }
                elseif ($value['level'] == RightEntity::CONTROLLER)
                {
                    if(isset($menu[$value['parentId']]))
                    {
                        $menu[$value['parentId']]['controllerList'][$value['id']]=$value;
                    }
                }
                elseif($value['level'] == RightEntity::ACTION)
                {
                    if(isset($value['parentId']))
                    {
                        $controllId=$value['parentId'];
                        if(isset($rightList[$controllId]['parentId']))
                        {
                            $moduleId=$rightList[$value['parentId']]['parentId'];
                            $menu[$moduleId]['controllerList'][$controllId]['actionList'][$value['id']]=$value;
                        }
                    }
                }
            }
            /*if(!empty($menu))
            {
                $menu['list']=$rightList;
            }*/
            return $menu;
        }
        return [];
    }

    /**根据名称过滤
     * @param array   $rightList        权限节点列表
     * @param string  $name             模块名称
     * @return mixed
     * @author 姜海强 <jhq0113@163.com>
     */
    public function filterByName($rightList,$name)
    {
        foreach ($rightList as $key=>$module)
        {
            if(
                mb_strpos($module['description'],$name,null,'UTF8') === false &&
                mb_strpos($module['name'],$name,null,'UTF8') ===false )
            {
                unset($rightList[$key]);
            }
        }
        return $rightList;
    }
}