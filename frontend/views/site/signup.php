<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use frontend\widgets\SummitAuthChoice;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>




<div class="sign-in">Sign up</div>
<div class="signup-row inner cover row ">
  
  <div class="col-xs-12 login-register-feedback hidden">
    <div class="alert alert-danger errors">
      Registering failed. Try again.
    </div>
  </div>
  
  <div class="col-xs-12">
    <p>&nbsp;</p>
  </div>
  <div class="col-xs-12">
    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
      
      <p>&nbsp;</p>
      <?= $form->field($model, 'username') ?>
      <?= $form->field($model, 'email') ?>
      <?= $form->field($model, 'password')->passwordInput() ?>
      <div class="form-group">
          <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
      </div>
      
      <div class="hi-icon-effect-1 hi-icon-effect-1a text-center">
        <a href="#" class="hi-icon icon-check done-check text-none" id="front-register-btn">Check</a>
      </div>
      	
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      
    <?php ActiveForm::end(); ?>
  </div>
</div>