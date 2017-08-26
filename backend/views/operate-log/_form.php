<?php
/**
 * Created by PhpStorm.
 * User: 康华茹
 * Date: 2016/12/30
 * Time: 18:29
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ar\AdminOperateLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-operate-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'admin_id')->textInput() ?>

    <?= $form->field($model, 'operate_id')->textInput() ?>

    <?= $form->field($model, 'operate_desc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operate_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'add_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新建' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
