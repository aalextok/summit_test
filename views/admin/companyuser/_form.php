<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'company_id')->dropDownList($companies,array('prompt'=>'-- Select company --'))->label('Company') ?>
   
    <?= $form->field($model, 'user_id')->dropDownList($users,array('prompt'=>'-- Select user --'))->label('User') ?>

    <?= $form->field($model, 'permissions')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'is_deleted')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
