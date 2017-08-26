<?php
/**
 * Created by PhpStorm.
 * User: 康华茹
 * Date: 2016/12/30
 * Time: 18:30
 */

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ar\AdminOperateLog */

$this->title = '添加操作日志';
$this->params['breadcrumbs'][] = ['label' => '管理操作日志', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-operate-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
