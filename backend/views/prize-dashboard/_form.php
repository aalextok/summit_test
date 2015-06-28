<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;

use backend\models\Competition;

use kartik\widgets\FileInput;
use yii\helpers\Url;

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
