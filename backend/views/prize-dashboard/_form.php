<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;

use backend\models\Competition;

/* @var $this yii\web\View */
/* @var $model backend\models\Prize */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prize-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'points')->textInput() ?>
    
    <?php
    $competitions = ArrayHelper::map(Competition::find()->all(), 'id', 'name');
    $competitions[0] = "";
    sort($competitions);
    ?>

    <?= $form->field($model, 'competition_id')->dropDownList($competitions)->label('Competition') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
