<?php
/**
 * 用户服务
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/11/28
 * Time: 21:13
 */

namespace backend\service;


use backend\components\StateHelper;
use backend\models\entity\AdminUserEntity;
use backend\models\entity\UserRoleEntity;
use common\utils\ComHelper;
use common\utils\SNHelper;

class UserService extends BaseService
{
    const PASSWORD_KEY='*bo8sdfa@#asfasf';

    const TOKEN_KEY='sdfsdf#@DFsdf45fSDfe';

    const TOKEN='ZhasdfIbadfao_AdMiN_toKeN';

    /**后台用户实体
     * @var AdminUserEntity
     * @author 姜海强 <jhq0113@163.com>
     */
    private $userEntity;

    /**用户角色实体
     * @var UserRoleEntity
     * @author 姜海强 <jhq0113@163.com>
     */
    private $userRoleEntity;

    /**
     * UserService constructor.
     */
    public function __construct()
    {
        $this->userEntity=new AdminUserEntity();
        $this->entity=$this->userEntity;
        $this->userRoleEntity=new UserRoleEntity();
    }

    /**获取登录信息
     * @return array|bool|mixed
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getLoginInfo()
    {
        $key=SNHelper::get(self::TOKEN);
        if($key)
        {
            $loginInfo=SNHelper::get($key);
            if(isset($loginInfo['id']))
            {
                $loginInfo=$this->userEntity->getInfoById((int)$loginInfo['id']);
                if(!empty($loginInfo))
                {
                    unset($loginInfo['password']);
                    return $loginInfo;
                }
            }
        }
        return false;
    }

    /**密码加密
     * @param string   $password    明文密码
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    private function dealPassword($password)
    {
        return md5($password.self::PASSWORD_KEY);
    }

    /**由明文密码获取密文密码
     * @param $password
     * @return string
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getSecretPassword($password)
    {
        return $this->dealPassword($password);
    }

    /**登陆
     * @param string    $userName     用户名
     * @param string    $password     密码
     * @return array|bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public function login($userName,$password)
    {
        $password=$this->dealPassword($password);
        $loginInfo=$this->userEntity->login($userName,$password);
        if(!empty($loginInfo))
        {
            $this->cacheLoginInfo($loginInfo);
            return true;
        }
        return false;
    }

    /**修改和注册
     * @param $info
     * @author 姜海强 <jhq0113@163.com>
     */
    public function save($info)
    {
        if(isset($info[':id']))
        {
            if($this->entity->checkEnableUpdate($info[':user_name'],$info[':id']))
            {
                return $this->entity->update($info);
            }
            ComHelper::retState(StateHelper::$ERROR_ADMIN_EXISTS);
        }
        if($this->entity->checkExist($info[':user_name']))
        {
            ComHelper::retState(StateHelper::$ERROR_ADMIN_EXISTS);
        }
        $info[':password']=$this->dealPassword($info[':password']);
        $this->entity->add($info);
    }

    /**持久化登陆信息
     * @param array    $loginInfo          登陆信息
     * @author 姜海强 <jhq0113@163.com>
     */
    public function cacheLoginInfo($loginInfo)
    {
        unset($loginInfo['password']);

        $key=md5(self::TOKEN_KEY.uniqid());

        SNHelper::set(self::TOKEN,$key);
        SNHelper::set($key,$loginInfo);
    }

    /**退出登录
     * @author 姜海强 <jhq0113@163.com>
     */
    public function loginOut()
    {
        $key=SNHelper::get(self::TOKEN);
        if($key)
        {
            SNHelper::del($key);
            SNHelper::del(self::TOKEN);
        }
    }

    /**设置超级管理员状态
     * @param int $id             管理员id
     * @param int $status         状态值
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function super($id,$status)
    {
        return $this->entity->super($id,$status);
    }

    /**修改密码
     * @param array  $info    修改密码信息
     * @author 姜海强 <jhq0113@163.com>
     */
    public function updatePassword($info)
    {
        $info[':password']=$this->dealPassword($info[':password']);
        $this->entity->updatePassword($info);
    }

    /**设置用户角色
     * @param int     $userId           用户ID
     * @param array   $roleList         角色id列表
     * @author 姜海强 <jhq0113@163.com>
     */
    public function setUserRole($userId,$roleList)
    {
        if($userId>0)
        {
            $this->userRoleEntity->delAllByUserId($userId);
            if(!empty($roleList))
            {
                $roleList=array_unique($roleList);
                $this->userRoleEntity->addRoles($userId,$roleList);
            }
        }
    }

    /**根据用户ID获取所有角色
     * @param $userId
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getUserRoleList($userId)
    {
        return $this->userRoleEntity->getRoleListByUserId($userId);
    }
}