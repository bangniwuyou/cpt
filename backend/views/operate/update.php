<?php
/**
 * Created by PhpStorm.
 * User: 康华茹
 * Date: 2016/12/30
 * Time: 18:47
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ar\AdminOperate */

$this->title = '修改操作项: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '管理员操作项', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="admin-operate-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
