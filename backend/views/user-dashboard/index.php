<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'firstname',
            'lastname',
            'gender',
            // 'email:email',
            // 'phone',
            // 'points',
            // 'summits',
            // 'meters_above_sea_level',
            // 'rank',
            // 'auth_key',
            // 'auth_key_expires',
            // 'password_hash',
            // 'password_reset_token',
            // 'status',
            // 'role',
            // 'created_at',
            // 'updated_at',
            // 'last_login',
            // 'login_count',
            // 'image',
            // 'image_hash',
            // 'facebook_id',
            // 'device_token',
            // 'platform',
            // 'client_notes:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
