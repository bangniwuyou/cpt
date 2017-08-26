<?php
/**
 * Created by PhpStorm.
 * User: 康华茹
 * Date: 2016/12/30
 * Time: 18:30
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ar\search\AdminOperateLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理操作日志';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-operate-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'admin_id',
            'admin_name',
            'operate_id',
            /*[
                'label' => '操作项',
                'format' => 'raw',
                'attribute' => 'desc',
                'options'=>['style'=>"width:150px;"]
            ],*/
            'operate_desc',
            [
                'label' => '操作时ip',
                'format' => 'raw',
                'attribute' => 'operate_ip',
                'value' => function($model){
                    return isset($model->operate_ip) ? long2ip($model->operate_ip): '';
                },
                'options'=>['style'=>"width:150px;"]
            ],
            'add_time',
        ],
    ]); ?>
</div>
