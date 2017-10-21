<?php
/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/10
 * Time: 15:21
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ar\AdminUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'true_name')->textInput(['maxlength' => true]) ?>

    <?php /* echo $form->field($model,'head_img')->widget(\common\widgets\upload\ImageWidget::class,[
            'clientOptions'=>[
                    'uploadUrl'=>'/up/input'
            ]
    ])*/ ?>

    <?php  /* echo $form->field($model,'head_img')->widget(\common\widgets\upload\VideoWidget::class,[
            'clientOptions'=>[
                    'uploadUrl'=>'/up/input'
            ]
    ])*/ ?>

    <?php  echo $form->field($model,'head_img')->widget(\common\widgets\upload\FileWidget::class,[
            'clientOptions'=>[
                    'uploadUrl'=>'/up/input'
            ]
    ]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_on')->textInput() ?>

    <?= $form->field($model, 'is_super_admin')->textInput() ?>

    <?= $form->field($model, 'last_login_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'add_time')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>