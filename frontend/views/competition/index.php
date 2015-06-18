<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
?>
<div ng-controller="ChallengeListCtrl">
  <input type="hidden" value="<?php echo Url::toRoute(["competition/view", 'id' => "replaceid"]); ?>" id="competition-view-base-uri" />
  
  <div class="vertical-middle" id="challenges-no-items">
  	<h1>No Challenges<br> at the moment</h1>
  </div>

  <div class="row" id="challenges-items">
  	
  	<div class="col-xs-6 challenge-item" ng-repeat="challenge in competition">
  		<a href="{{challenge.uri}}">
  			<?php echo Html::img('@web/img/challenge.jpg', ['class' => 'img-responsive']) ?>
  			<div class="texts">
  				<h1>{{challenge.name}}</h1>
  				{{challenge.description}}
  			</div>
  		</a>
  	</div>
  	
  </div>
</div>

