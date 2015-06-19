<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="vertical-middle-cong">
  <?php $form = ActiveForm::begin(['id' => 'visit-form']); ?>
	<h1>Congratulations</h1>
	You finished your goal, please insert code here:
	
    <?php echo $form->field( $model, 'code', ['inputOptions' => [
        'class' => 'form-control input-des-congrats', 
        'type' => 'text', 
        "placeholder" => "123 123 123"
    ]] )->label(false); ?>
      
	<div class="hi-icon-effect-1 hi-icon-effect-1a ">
		<a href="#" class="hi-icon icon-check done-check" id="visit-add-btn">Check</a>
	</div>
  <?php ActiveForm::end(); ?>
</div>
