<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
//use yii\jui\DatePicker;

use backend\models;

/* @var $this yii\web\View */
/* @var $model backend\models\Competition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="competition-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?php 
    
    $activities = ArrayHelper::map(models\Activity::find()->all(), 'id', 'name');
    $activities[0] = "";
    sort($activities);
//    
//    echo '<label class="control-label">Activity</label>';
//    echo Html::activeDropDownList($model, 'activity_id', $activities);
    
    echo $form->field($model, 'activity_id')->dropDownList($activities)->label('Activity');
    ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    
    <?php 
    echo "<label class='control-label'>{$model->getAttributeLabel('open_time')}</label>";
    echo DateControl::widget([
        'model' => $model,
        'attribute' => 'open_time',
        'type'=>DateControl::FORMAT_DATE,
        'displayFormat' => 'php:D, Y-m-d',
        'saveFormat' => 'php:U'
    ]);
    ?>
    
    <?php 
    echo "<label class='control-label'>{$model->getAttributeLabel('close_time')}</label>";
    echo DateControl::widget([
        'model' => $model,
        'attribute' => 'close_time',
        'type'=>DateControl::FORMAT_DATE,
        'displayFormat' => 'php:D, Y-m-d',
        'saveFormat' => 'php:U'
    ]);
    ?>
    
    <?php 
    //
    $value = array_map(function($o){
        return $o->id;
    }, $model->places);
    
    $places = models\Place::find()->all();    
    $data = [];
    
    foreach ($places as $place){
        $data[$place->id] = "{$place->name}";
    }
    
    echo '<label class="control-label">Places</label>';
    echo Select2::widget([
        'name' => 'places',
        'data' => $data,
        'value' => $value,
        'options' => [
            'multiple' => true,
            'placeholder' => 'Select a place...'
        ],

    ]);
    ?>

    <!--<?= $form->field($model, 'achievements_by_places')->textInput() ?> -->

    <!--<?= $form->field($model, 'activity_id')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
