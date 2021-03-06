<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Place */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Places', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-view">

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
            [
                'attribute' => 'activities',
                'value' => $model->getActivitiesNames(),
            ],
            'code',
            'name',
            'description:ntext',
            'meters_above_sea_level',
            'distance',
            'points',
            'latitude',
            'longtitude',
            'address',
            'location'
        ],
    ]) ?>
    
    <?php
//    $images = [];
//    foreach ($model->images as $image){
//        $images[] = [
//            'title' => $image->name,
//            'href' => Yii::getAlias('@web').'/'.$image->location,
//        ];
//    }
//    
//    echo dosamigos\gallery\Carousel::widget([
//        'items' => $images,
//        'json' => true,
//    ]);
    ?>

</div>
