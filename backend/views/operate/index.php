<?php
/**
 * Created by PhpStorm.
 * User: 康华茹
 * Date: 2016/12/30
 * Time: 18:46
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ar\search\AdminOperateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员操作项';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-operate-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新建操作项', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'map',
            'desc',
            'add_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
