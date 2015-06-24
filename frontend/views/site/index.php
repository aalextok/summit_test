<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use frontend\widgets\SummitAuthChoice;
?>

<div class="col-xs-12 login-register-feedback hidden">
  <div class="alert alert-danger errors">
    Login failed. Try again.
  </div>
</div>

<div class="col-xs-6">
  <?php /*<a href="#" class="btn btn-block btn-facebook">Log in with facebook</a>*/ ?>
    
  <?php 
  /*
    echo SummitAuthChoice::widget([
       'baseAuthUrl' => ['site/auth']
  ]) */?>
 
 <a class="btn btn-block btn-facebook btn-facebook-login" onclick="checkFacebookLoginState();" href="#">Log in with facebook</a>
 
  <div class="sign-up">
    Don't have an account? <a href="<?php echo Url::toRoute("site/signup"); ?>">SIGN UP HERE</a>
  </div>
</div>
<div class="col-xs-6">
  <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'pull-right']]); ?>
    
    <?php echo $form->field( $model, 'username', ['inputOptions' => [
        "id" => "loginEmail", 
        'class' => 'form-control input-des', 
        'type' => 'email', 
        "placeholder" => "E-mail address"
      ]] )->label(false); ?>
      
    <?php echo $form->field( $model, 'password', ['inputOptions' => [
        "id" => "loginPassword", 
        'class' => 'form-control input-des', 
        'type' => 'password', 
        "placeholder" => "Password"
      ]] )->label(false); ?>
    
    <a href="<?php echo Url::toRoute("site/request-password-reset"); ?>">Forgot password?</a>
    
    <div class="hi-icon-effect-1 hi-icon-effect-1a text-center">
      <a href="#" class="hi-icon icon-check done-check text-none" id="front-login-btn">Check</a>
    </div>
    	
    <?php /*
    <?php echo $form->field($model, 'password')->passwordInput() ?>
    <?php echo $form->field($model, 'rememberMe')->checkbox() ?>
    <div class="form-group">
        <?php echo Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
    <?php echo Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    */ ?>
  <?php ActiveForm::end(); ?>
</div>
