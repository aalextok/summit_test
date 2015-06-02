<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->id;
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
            'username',
            'firstname',
            'lastname',
            'gender',
            'email:email',
            'phone',
            'points',
            'summits',
            'meters_above_sea_level',
            'rank',
            'auth_key',
            'auth_key_expires',
            'password_hash',
            'password_reset_token',
            'status',
            'role',
            'created_at',
            'updated_at',
            'last_login',
            'login_count',
            'image',
            'image_hash',
            'facebook_id',
            'device_token',
            'platform',
            'client_notes:ntext',
        ],
    ]) ?>

</div>
