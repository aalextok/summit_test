<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use backend\models\Activity;
use backend\models\Place;
use yii\helpers\ArrayHelper;

use kartik\widgets\FileInput

/* @var $this yii\web\View */
/* @var $model backend\models\Place */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="place-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data']
    ]); ?>
    
    <?php
        $model->activity_ids = array_map(function($o) { return $o->id; }, $model->activities);
        $allActivities = ArrayHelper::map(Activity::find()->asArray()->all(), 'id', 'name');
        
    ?>
    
    <?= Html::activeCheckboxList($model, 'activity_ids', $allActivities) ?>
    
    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'meters_above_sea_level')->textInput() ?>

    <?= $form->field($model, 'distance')->textInput() ?>

    <?= $form->field($model, 'points')->textInput() ?>

    <?= $form->field($model, 'latitude')->textInput() ?>

    <?= $form->field($model, 'longtitude')->textInput() ?>
    
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    
    <?php
    $locations = ArrayHelper::map(\backend\models\Location::find()->all(), 'id', 'name');
    //$locations[0] = "";
    sort($locations);
    $locations = array_combine($locations, $locations);
    
    echo $form->field($model, 'location')->dropDownList($locations)->label('Location');
    
    ?>
    
    <?php
//    echo $form->field($model, 'img[]')->widget(FileInput::classname(), [
//        'options' => ['multiple' => true, 'accept' => 'image/*'],
//        'pluginOptions' => [
//            'previewFileType' => 'image',
//            'initialPreview' => [
//                Html::img("", ['class'=>'file-preview-image']),
//            ],
//            'overwriteInitial' => false,
//            
//        ],
//        
//    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
