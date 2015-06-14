<?php
/* @var $this yii\web\View */
?>
<div ng-controller="ChallengeListCtrl">
  <div class="vertical-middle" id="challenges-no-items">
  	<h1>No Challenges<br> at the moment</h1>
  </div>

  <div class="row" id="challenges-items">
  	
  	<div class="col-xs-6 challenge-item" ng-repeat="challenge in competition">
  		<a href="#">
  			<img src="img/challenge.jpg" class="img-responsive">
  			<div class="texts">
  				<h1>{{challenge.name}}</h1>
  				{{challenge.description}}
  			</div>
  		</a>
  	</div>
  	
  </div>
</div>

