<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
?>

 <input type="hidden" value="background:url(<?php echo Url::toRoute("img/bg-gray-challenge.png"); ?>) no-repeat;background-position: center center;background-size: cover;" id="main-style-replace" />
 <input type="hidden" value="<?php echo $competition->id; ?>" id="competition-view-id" />
 <input type="hidden" value="<?php echo Url::toRoute(["place/view", 'id' => "replaceid"]); ?>" id="place-view-base-uri" />
 
<div class="row" ng-controller="CompetitionViewCtrl">
  <div class="col-xs-12 back-link">
    <a href="<?php echo Url::toRoute(["competition/index"]); ?>">
      <?php echo Html::img('@web/img/back-arrow.png', ['class' => 'm-r-15', 'style' => 'height: 40px;']) ?>Back
    </a> 
    <h1><?php echo $competition->name; ?></h1>
  </div>

  <div id="competition-place-items">
      <div class="col-xs-6" ng-repeat="place in places">
    	<div class="place-item">
    		<div class="challenge-place clearfix">
				<div class="challenge-image pull-left">
        			<?php echo Html::img('@web/img/mountain.jpg', ['class' => 'img-circle']) ?>
        			<img src="<?php echo Url::toRoute("img/done-icon.png"); ?>" ng-class="isDone(place)" style="style: 34px" />
    			</div>
    			<div class="challenge-title"><a href="{{place.uri}}" onclick="Show_Div(Div_5)">{{place.name}}</a></div>
    			<div class="challenge-height pull-right"><span>{{place.meters_above_sea_level}}</span> Meters above <br>sealevel</div>
    		</div>
    		<div class="challenge-menu clearfix" id="Div_5" ng-class="isDoneMenu(place)">
    			<div class="challenge-add-code pull-left">
    				<a href="<?php echo Url::toRoute("place/visit"); ?>">
    				  <?php echo Html::img('@web/img/plus-icon-white.png', ['class' => 'm-r-15', 'style' => 'width: 35px;']) ?>
    			      Add code
    			    </a>
    			</div>
    			<div class="challenge-more pull-right">
        		    <a href="{{place.uri}}">
        			  <?php echo Html::img('@web/img/info-icon.png', ['class' => 'm-l-15', 'style' => 'width: 35px;']) ?>
        			</a>
    			</div>
    		</div>
    	</div>
      </div>
    
  </div>
  
</div>




