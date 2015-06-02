<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Prizes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prize-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Prize', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description:ntext',
            'points',
            'summits',
            // 'meters_above_sea_level',
            // 'distance',
            // 'competition_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
