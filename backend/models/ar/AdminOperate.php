<?php
/**
 * Created by PhpStorm.
 * User: 康华茹
 * Date: 2016/12/30
 * Time: 18:41
 */

namespace backend\models\ar;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%admin_operate}}".
 *
 * @property integer $id
 * @property string $map
 * @property string $desc
 * @property string $add_time
 */
class AdminOperate extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_operate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_time'], 'safe'],
            [['map'], 'string', 'max' => 255],
            [['desc'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'map' => '模块控制器操作地图',
            'desc' => '操作描述',
            'add_time' => '添加时间',
        ];
    }

    /** 根据id获取信息
     * @param int $id
     * @return mixed
     * @author 康华茹 <kanghuaru@zhibo.tv>
     */
    public function getInfoById($id){
        return $this->find()->select('*')->from(self::tableName())->where(['id'=>$id])->asArray()->one();
    }
}
