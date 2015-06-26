<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="vertical-middle-cong" ng-controller="PlaceVisitCtrl">
  <?php $form = ActiveForm::begin(['id' => 'visit-form']); ?>
	<h1>Congratulations</h1>
	You finished your goal, please insert code here:
  	
    <div class="col-xs-12 feedbacks">
    	<div class="alert alert-success hidden">
    	  Visit added.
    	</div>
    	<div class="alert alert-danger hidden">
    	  Adding visit failed. Try again. 
    	</div>
      <div class="ajax-content-loading hidden">
        <?php echo Html::img('@web/img/loading-big.gif') ?>
    	  Loading ...
      </div>
    </div>
    
	<input type="text" class="form-control input-des-congrats" id="visitPlaceCode" ng-init="formData.place_code='<?php echo $model->place_code; ?>'" value="<?php echo $model->place_code; ?>" ng-model="formData.place_code" placeholder="123 123 123" />
	
	<div class="hi-icon-effect-1 hi-icon-effect-1a ">
		<a href="#" class="hi-icon icon-check done-check" id="visit-add-btn" ng-click="proccessForm( $event )">Check</a>
	</div>
  <?php ActiveForm::end(); ?>
</div>
