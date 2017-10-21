<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/10
 * Time: 15:19
 */

namespace backend\models\ar\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ar\AdminUser;

/**
 * AdminUserSearch represents the model behind the search form about `backend\models\ar\AdminUser`.
 */
class AdminUserSearch extends AdminUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_on', 'is_super_admin', 'last_login_ip'], 'integer'],
            [['user_name', 'true_name', 'head_img', 'password', 'add_time', 'update_time'], 'safe'],
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
        $query = AdminUser::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_on' => $this->is_on,
            'is_super_admin' => $this->is_super_admin,
            'last_login_ip' => $this->last_login_ip,
            'add_time' => $this->add_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'true_name', $this->true_name])
            ->andFilterWhere(['like', 'head_img', $this->head_img])
            ->andFilterWhere(['like', 'password', $this->password]);

        return $dataProvider;
    }
}
