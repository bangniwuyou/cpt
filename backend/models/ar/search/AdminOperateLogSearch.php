<?php
/**
 * Created by PhpStorm.
 * User: 康华茹
 * Date: 2016/12/30
 * Time: 18:26
 */

namespace backend\models\ar\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ar\AdminOperateLog;

/**
 * AdminOperateLogSerach represents the model behind the search form about `\backend\models\AdminOperateLog`.
 */
class AdminOperateLogSearch extends AdminOperateLog
{
    public $operate_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'admin_id', 'operate_id'], 'integer'],
            [['operate_desc','admin_name','operate_ip'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AdminOperateLog::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'defaultOrder' => [
                    'add_time' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }



        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'admin_id' => $this->admin_id,
            'operate_id' => $this->operate_id,
            'add_time' => $this->add_time,
        ]);

        if(isset($this->operate_ip) && !empty($this->operate_ip))
        {
            $query->andFilterWhere([
                'operate_ip' =>ip2long($this->operate_ip),
            ]);
        }

        $query->andFilterWhere([
            'like', 'operate_desc', $this->operate_desc
        ]);

        $query->andFilterWhere([
            'like', 'admin_name', $this->admin_name
        ]);

        return $dataProvider;
    }
}
