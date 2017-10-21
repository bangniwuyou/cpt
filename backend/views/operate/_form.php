<?php
/**
 * Created by PhpStorm.
 * User: 康华茹
 * Date: 2016/12/30
 * Time: 18:45
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ar\AdminOperate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-operate-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'map')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->widget(\bootstrap_datetime_picker\DatetimePickerWidget::class) ?>
    <?php //echo $form->field($model, 'desc')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新建' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
