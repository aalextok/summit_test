<?php
/* @var $this yii\web\View */
$this->title = 'Welcome';
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="row" ng-controller="DashBoardCtrl">
	<div class="nav-top">
		<div class="nav-activities pull-left">
			<div class="dropdown">
				<a class="btn-menu menu-link dropdown-toggle" type="button" id="activitiesMenu" data-toggle="dropdown" aria-expanded="true">
				<span class="pull-left">All activities</span>
				  <?php echo Html::img('@web/img/dropdown.png', ['class' => 'pull-right', 'style' => 'height: 22px;']) ?>
				</a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="activitiesMenu">
				  <li role="presentation"><a role="menuitem" tabindex="-1" href="#">All activities</a></li>
					<?php foreach($allActivities as $k => $activity){ ?>
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><?php echo $activity->name; ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
		
		<div class="nav-activities pull-left">
			<div class="dropdown">
				<a class="btn-menu menu-link dropdown-toggle" type="button" id="locationMenu" data-toggle="dropdown" aria-expanded="true">
				<span class="pull-left">Location</span>
				  <?php echo Html::img('@web/img/dropdown.png', ['class' => 'pull-right', 'style' => 'height: 22px;']) ?>
				</a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="locationMenu">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Place 1</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Place 2</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Place 3</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Place 4</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Place 5</a></li>
				</ul>
			</div>
		</div>
		<div class="nav-map pull-right">
			<a href="#">
			  <?php echo Html::img('@web/img/map-icon.png', ['style' => 'height: 22px;']) ?>
			</a>
		</div>
	</div>
	
  <input type="hidden" value="<?php echo Url::toRoute(["place/view", 'id' => "replaceid"]); ?>" id="place-view-base-uri" />
  
  <div class="vertical-middle" id="places-no-items">
  	<h1>No places<br> at the moment  </h1>
  </div>
  
  <div class="row" id="places-items">
	
	<div class="col-xs-6 m-b-40" ng-repeat="place in places">
		<div class="event clearfix m-0 b-r-top">
			<div class="event-img pull-left">
			  <?php echo Html::img('@web/img/hike.jpg', ['class' => 'img-circle']) ?>
			  <img src="<?php echo Url::toRoute("img/done-icon.png"); ?>" ng-class="isDone(place)" style="style: 34px" />
			</div>
			<div class="event-description pull-left">
				<div class="event-title"><a href="{{place.uri}}">{{place.name}}</a></div>
				<div class="event-when">
				  {{place.description}}
				</div>
			</div>
		</div>
		<div class="green-bottom clearfix">
			<div class="place-loc pull-left">{{place.address}}</div>
			<div class="place-more pull-right">
				<a href="{{place.uri}}">
				  <?php echo Html::img('@web/img/arrow-right.png', ['style' => 'height: 22px;']) ?>
				</a>
			</div>
		</div>
	</div>'
	
  </div>
</div>