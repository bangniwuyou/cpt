<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/10
 * Time: 15:13
 */

namespace backend\models\ar;

use Yii;

/**
 * This is the model class for table "{{%admin_user}}".
 *
 * @property integer $id
 * @property string $user_name
 * @property string $true_name
 * @property string $head_img
 * @property string $password
 * @property integer $is_on
 * @property integer $is_super_admin
 * @property string $last_login_ip
 * @property string $add_time
 * @property string $update_time
 */
class AdminUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_on', 'is_super_admin', 'last_login_ip'], 'integer'],
            [['add_time', 'update_time'], 'safe'],
            [['user_name', 'true_name', 'password'], 'string', 'max' => 32],
            [['head_img'], 'string', 'max' => 1024],
            [['user_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '管理员ID',
            'user_name' => '管理员登录名',
            'true_name' => '管理员真实姓名',
            'head_img' => '头像',
            'password' => '密码',
            'is_on' => '启用状态',
            'is_super_admin' => '是否超管',
            'last_login_ip' => '上次登录ip',
            'add_time' => '注册时间',
            'update_time' => '更新时间',
        ];
    }
}