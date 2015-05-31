<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

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
            'email:email',
            'username',
            'is_superadmin:boolean',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <h3>Companies</h3>

    <?= GridView::widget([
        'dataProvider' => $companyUsers,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'company.name',
            'permissions',

            ['class' => 'yii\grid\ActionColumn',
                'controller' => 'admin/companyuser'
            ],
        ],
    ]); ?>
    <p>
        <?= Html::a('Add company', ['admin/companyuser/create'], ['class' => 'btn btn-success']) ?>
    </p>

</div>
