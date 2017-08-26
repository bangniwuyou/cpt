<?php
/**
 * Created by PhpStorm.
 * User: 康华茹
 * Date: 2016/12/30
 * Time: 18:31
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ar\AdminOperateLog */

$this->title = $desc;
$this->params['breadcrumbs'][] = ['label' => '管理操作日志', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-operate-log-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定删除这条日志吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'admin_id',
            [
                'label'=>'管理员昵称',
                'value'=>$truename
            ],
            [
                'label'=>'操作项',
                'value'=>$desc
            ],
            //'operate_id',
            'operate_desc',
            //'operate_ip',
            [
                'label'=>'操作时ip',
                'value'=>long2ip($model->operate_ip),
            ],
            'add_time',
        ],
    ]) ?>

</div>
