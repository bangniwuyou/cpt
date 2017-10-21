<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/7
 * Time: 13:48
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ar\AdminOperate */
/* @var $form yii\widgets\ActiveForm */
//$model->desc =date('Y-m-d H:i:s');
?>

<div class="admin-operate-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'map')->textInput(['maxlength' => true]) ?>

    <?php //$form->field($model, 'desc')->textInput(['maxlength' => true]) ?>


    <?php //echo $form->field($model, 'desc')->widget(\bootstrap_datetime_picker\DatetimePickerWidget::class); ?>


    <?php  echo $form->field($model, 'desc')->widget(\bootstrap_fileinput\FileinputWidget::class,[
            'type' => \bootstrap_fileinput\utils\UploadHelper::VIDEO,
            'fileinputOptions'=>[
                'maxFileCount' => 1
            ]
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新建' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

