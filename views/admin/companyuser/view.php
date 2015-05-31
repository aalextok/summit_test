<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyUser */

$this->title = $model->company->name.' - '.$model->user->name;
$this->params['breadcrumbs'][] = ['label' => 'Company Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-user-view">

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
            [
                'label' => 'Company name',
                'value' => $model->company->name
            ],
            [
                'label' => 'User name',
                'value' => $model->user->name
            ],
            'permissions',
            'is_deleted:boolean',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
