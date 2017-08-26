<?php

namespace backend\models\ar;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%admin_operate_log}}".
 *
 * @property string $id
 * @property integer $admin_id
 * @property integer $operate_id
 * @property string $operate_desc
 * @property string $operate_ip
 * @property string $add_time
 * @property string $admin_name
 */
class AdminOperateLog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_operate_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'operate_id', 'operate_ip'], 'integer'],
            [['add_time'], 'safe'],
            [['operate_desc'], 'string', 'max' => 500],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin_id' => '管理员ID',
            'admin_name' => '管理员昵称',
            'operate_id' => '操作类型ID',
            'operate_desc' => '操作时描述',
            'operate_ip' => '操作时IP',
            'add_time' => '操作时间',
        ];
    }
}
