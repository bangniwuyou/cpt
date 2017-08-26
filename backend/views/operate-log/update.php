<?php
/**
 * Created by PhpStorm.
 * User: 康华茹
 * Date: 2016/12/30
 * Time: 18:31
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ar\AdminOperateLog */

$this->title = '修改管理操作日志: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '管理操作日志', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="admin-operate-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
