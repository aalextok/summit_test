<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use backend\models\Activity;
use backend\models\Place;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\widgets\FileInput;

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
        $model->images;
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
    
    $initialPreview = [];
    $initialPreviewConfig = [];
    
    foreach($model->images as $image){
        $initialPreview[] = Html::img(Yii::getAlias('@web').'/'.$image->location, ['class'=>'file-preview-image', 'alt' => $image->name, 'title' => $image->name]);
    
        $initialPreviewConfig[] = [
            'caption' => $image->name,
            'url' => Url::to(['site/delete-image', 'id' => $image->id]),
            'key' => $image->id,
        ];
    }
    
    echo "<label class='control-label'>{$model->getAttributeLabel('images')}</label>";
    echo FileInput::widget([
        'name' => 'images',
        'options'=>[
            'multiple' => true,
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'uploadUrl' => Url::to([
                'site/upload-images', 
                'model' => $model->model, 
                'classname' => $model->className(),
                'model_id' => $model->id,
            ]),            
            'uploadExtraData' => [
            ],
            'initialPreview' => $initialPreview,
            'initialPreviewConfig' => $initialPreviewConfig,
            'overwriteInitial' => false,
            'maxFileCount' => 10
        ],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
