<?php
/**
 * Created by PhpStorm.
 * User: 康华茹
 * Date: 2016/12/30
 * Time: 18:46
 */

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ar\AdminOperate */

$this->title = '新建操作项';
$this->params['breadcrumbs'][] = ['label' => '管理员操作项', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-operate-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
