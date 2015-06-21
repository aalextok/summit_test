<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

use frontend\widgets\PlaceMapRenderer;

?>

 <input type="hidden" value="background:url(<?php echo Url::toRoute("img/bg-gray-challenge.png"); ?>) no-repeat;background-position: center center;background-size: cover;" id="main-style-replace" />

<div class="row">
  <div class="col-xs-12 back-link">
    <a href="#" onclick="if(document.referrer) {window.open(document.referrer,'_self');} else {history.go(-1);} return false;">
      <?php echo Html::img('@web/img/back-arrow.png', ['class' => 'm-r-15', 'style' => 'height: 40px;']) ?>Back
    </a> 
    <h1><?php echo $place->name; ?></h1>
  </div>
  
  <div class="col-xs-12">
    <?php echo $place->description; ?>
  </div>
  
  <div class="col-xs-12">
    <div class="challenge-height pull-left"><span>1588</span> Meters above <br>sealevel</div>
  </div>
  
  <div class="col-xs-12">
    <?php echo PlaceMapRenderer::widget( ['place' => $place] ); ?>
  </div>
</div>

