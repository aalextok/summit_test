<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use backend\models\Place;


/* @var $this yii\web\View */
/* @var $model backend\models\Competition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Competitions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$model->activity;
?>
<div class="competition-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code',
            'name',
            'description:ntext',
            'achievements_by_places',
            //'activity_id',
            [
                'label' => 'Activity',
                'value' => isset($model->activity) ? $model->activity->name : '',
            ]
        ],
    ]) ?>
    
    <?php
    
    $placesIds = array_map(function($o){
        return $o->id;
    }, $model->places);
    
    $placesDataProvider = new ActiveDataProvider([
        'query' => Place::find()->where(['id' => $placesIds]),
    ]);
    ?>
    <label class="control-label">Places:</label>
    <?= GridView::widget([
        'dataProvider' => $placesDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'code',
            'name',
            'description:ntext',
            'meters_above_sea_level',
            'distance',
            'points',
            // 'latitude',
            // 'longtitude',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
