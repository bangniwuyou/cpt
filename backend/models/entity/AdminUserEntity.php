<?php
/**
 * 后台用户
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2016/11/28
 * Time: 18:51
 */

namespace backend\models\entity;


use common\utils\ComHelper;

/**后台管理员
 * Class AdminUserEntity
 * @package backend\models\entity
 * @author 姜海强 <jhq0113@163.com>
 */
class AdminUserEntity extends BaseEntity
{
    public $tableName='{{%admin_user}}';

    public $fields=[
        'id',
        'user_name',
        'true_name',
        'password',
        'is_on',
        'is_super_admin',
        'last_login_ip',
        'add_time',
        'update_time'
    ];

    /**根据用户id获取用户信息
     * @param int   $id          后台管理员用户id
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getInfoById($id)
    {
        $info=$this->query(
            'SELECT '.$this->formatFields($this->fields).' FROM '.$this->getTableName().' WHERE `id`=:id LIMIT 1',
            [
               ':id'=>$id
            ],
            true
        );
        if(!empty($info))
        {
            $info[0]['lastLoginIp']=long2ip($info[0]['lastLoginIp']);
            return $info[0];
        }
        return [];
    }

    /**根据用户id获取可用用户
     * @param int   $id    后台管理员用户id
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function getEnableInfoById($id)
    {
        $info= $this->query(
            'SELECT '.$this->formatFields($this->fields).' FROM '.$this->getTableName().' WHERE `id`=:id AND `is_on`=1 LIMIT 1',
            [
                ':id'=>$id
            ],
            true
        );
        if(!empty($info))
        {
            $info[0]['lastLoginIp']=long2ip($info[0]['lastLoginIp']);
            return $info[0];
        }
        return [];
    }

    /**登陆
     * @param string  $userName            用户名
     * @param string  $secretPassword      密码
     * @return array
     * @author 姜海强 <jhq0113@163.com>
     */
    public function login($userName,$secretPassword)
    {
        $info=$this->query(
            'SELECT '.$this->formatFields($this->fields).' FROM '.$this->getTableName().' WHERE `user_name`=:user_name AND `password`=:password LIMIT 1',
            [
                ':user_name'=>$userName,
                ':password'=>$secretPassword
            ],
            true
        );
        if(!empty($info))
        {
            $this->autoUpdate($info[0]['id']);
            return $info[0];
        }
        return [];
    }

    /**自动更新登录ip
     * @param int    $id    后台用户id
     * @author 姜海强 <jhq0113@163.com>
     */
    public function autoUpdate($id)
    {
        $ip=ComHelper::getClientLongIp();
        $this->execute(
            'UPDATE '.$this->getTableName().' SET last_login_ip=:last_login_ip WHERE `id`=:id LIMIT 1',
            [
                ':last_login_ip'=>$ip,
                ':id'=>$id
            ]
            );
    }

    /**设置超级管理员
     * @param int    $id        管理员ID
     * @param int    $status    状态值
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function super($id,$status)
    {
        return $this->execute(
            'UPDATE '.$this->getTableName().' SET is_super_admin=:is_super_admin WHERE `id`=:id LIMIT 1',
            [
                ':is_super_admin'=>(int)$status,
                ':id'=>(int)$id
            ]
        );
    }

    /**校验管理员是否存在
     * @param $userName
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public function checkExist($userName)
    {
        $info=$this->queryOne(
            'SELECT `id` FROM '.$this->getTableName().' WHERE `user_name`=:user_name',
            [
                ':user_name'=>$userName
            ],
            true
        );
        if(!empty($info))
        {
            return true;
        }
        return false;
    }

    /**校验是否可以更新
     * @param string $userName     登录用户名
     * @param int    $id           管理员id
     * @return bool
     * @author 姜海强 <jhq0113@163.com>
     */
    public function checkEnableUpdate($userName,$id)
    {
        $info=$this->queryOne(
            'SELECT `id` FROM '.$this->getTableName().' WHERE `user_name`=:user_name',
            [
                ':user_name'=>$userName
            ],
            true
        );
        if(!empty($info))
        {
            return $info['id'] == $id;
        }
        return true;
    }

    /**添加管理员
     * @param array   $info        管理员信息
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function add($info)
    {
        return $this->execute(
            'INSERT INTO '.$this->getTableName().'(`user_name`,`true_name`,`password`,`is_on`,`add_time`)VALUES(:user_name,:true_name,:password,:is_on,\''.date('Y-m-d H:i:s').'\')',
            $info
        );
    }

    /**更新
     * @param array   $info    管理员信息
     * @return int
     * @author 姜海强 <jhq0113@163.com>
     */
    public function update($info)
    {
        return $this->execute(
            'UPDATE '.$this->getTableName().' SET `user_name`=:user_name,`true_name`=:true_name,`is_on`=:is_on WHERE `id`=:id LIMIT 1',
            $info
        );
    }

    /**修改密码
     * @param array  $info      修改密码信息
     * @author 姜海强 <jhq0113@163.com>
     */
    public function updatePassword($info)
    {
        return $this->execute(
            'UPDATE '.$this->getTableName().' SET `password`=:password WHERE `id`=:id LIMIT 1',
            $info
        );
    }
}