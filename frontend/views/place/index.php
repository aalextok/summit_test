<?php
/* @var $this yii\web\View */
$this->title = 'Activities';
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\PlacesMapRenderer;
?>
<div class="row" ng-controller="PlacesListCtrl">
	<div class="nav-top">
		<div class="nav-activities pull-left">
			<div class="dropdown">
				<a class="btn-menu menu-link dropdown-toggle" type="button" id="activitiesMenu" data-toggle="dropdown" aria-expanded="true">
				<span class="pull-left" id="activity-selector-selected">All activities</span>
				  <?php echo Html::img('@web/img/dropdown.png', ['class' => 'pull-right', 'style' => 'height: 22px;']) ?>
				</a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="activitiesMenu">
				    <li role="presentation"><a role="menuitem" tabindex="-1" ng-click="filterPlacesByActivity($event, 0, '');" href="#">All activities</a></li>
					<?php foreach($allActivities as $k => $activity){ ?>
					  <li role="presentation"><a role="menuitem" tabindex="-1" ng-click="filterPlacesByActivity($event, '<?php  echo $activity->id; ?>', '<?php  echo $activity->name; ?>');" href="#"><?php echo $activity->name; ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
		
		<div class="nav-activities pull-left">
			<div class="dropdown">
				<a class="btn-menu menu-link dropdown-toggle" type="button" id="locationMenu" data-toggle="dropdown" aria-expanded="true">
				<span class="pull-left" id="location-selector-selected">Location</span>
				  <?php echo Html::img('@web/img/dropdown.png', ['class' => 'pull-right', 'style' => 'height: 22px;']) ?>
				</a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="locationMenu">
				    <li role="presentation"><a role="menuitem" tabindex="-1" ng-click="filterPlacesByLocation($event, 0, '');" href="#">All locations</a></li>
    				<?php foreach($allLocations as $k => $location){ ?>        
    				  <li role="presentation"><a role="menuitem" tabindex="-1" ng-click="filterPlacesByLocation($event, '<?php  echo $location->id; ?>', '<?php  echo $location->name; ?>');" href="#"><?php echo $location->name; ?></a></li>
    				<?php } ?>
				</ul>
			</div>
		</div>
		<div class="nav-map pull-right">
			<a href="#" ng-click="toggleMapView()">
			  <?php echo Html::img('@web/img/map-icon.png', ['style' => 'height: 22px;']) ?>
			</a>
		</div>
	</div>
	
  <input type="hidden" value="<?php echo Url::toRoute(["place/view", 'id' => "replaceid"]); ?>" id="place-view-base-uri" />
  
  <div class="vertical-middle hidden" id="places-no-items">
  	<h1>No places<br /> at the moment  </h1>
  </div>
  
  <div class="hidden" id="places-items-on-map">
    <div id="mapcanvas"></div>
    <br />
  </div>
  
  <div class="row hidden" id="places-items">
	
	<div class="col-xs-6 m-b-40" ng-repeat="place in places">
		<div class="event clearfix m-0 b-r-top">
			<div class="event-img pull-left">
			  <?php echo Html::img('@web/img/hike.jpg', ['class' => 'img-circle']) ?>
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
	</div>
	
  </div>
</div>