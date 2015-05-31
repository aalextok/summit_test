<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-view">

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
            'name',
            'domain',
        ],
    ]) ?>

    <h3>Users</h3>

    <?= GridView::widget([
        'dataProvider' => $users,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'user.name',
            'user.username',
            'permissions',

            ['class' => 'yii\grid\ActionColumn',
                'controller' => 'admin/companyuser'
            ],
        ],
    ]); ?>
    <p>
        <?= Html::a('Add user', ['admin/companyuser/create'], ['class' => 'btn btn-success']) ?>
    </p>

</div>
