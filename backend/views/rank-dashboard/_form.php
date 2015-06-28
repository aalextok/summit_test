<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Rank */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rank-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rank')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'points')->textInput() ?>
    
    <?php
    
    $initialPreview = [];
    $initialPreviewConfig = [];
    $replaceId = null;
    
    if(!empty($model->image)){
        $initialPreview[] = Html::img(Yii::getAlias('@web').'/'.$model->image->location, ['class'=>'file-preview-image', 'alt' => $model->image->name, 'title' => $model->image->name]);

        $initialPreviewConfig[] = [
            'caption' => $model->image->name,
            'url' => Url::to(['site/delete-image', 'id' => $model->image->id]),
            'key' => $model->image->id,
        ];
        
        $replaceId = $model->image->id;
    }
    
    echo "<label class='control-label'>{$model->getAttributeLabel('image')}</label>";
    echo FileInput::widget([
        'name' => 'images',
        'options'=>[
            'multiple' => false,
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'uploadUrl' => Url::to([
                'site/upload-images', 
                'model' => $model->model, 
                'classname' => $model->className(),
                'model_id' => $model->id,
                'replace_id' => $replaceId,
            ]),            
            'uploadExtraData' => [
            ],
            'initialPreview' => $initialPreview,
            'initialPreviewConfig' => $initialPreviewConfig,
            'overwriteInitial' => true,
            //'maxFileCount' => 1,
        ],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
