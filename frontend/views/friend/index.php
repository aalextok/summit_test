<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="row UserSearchListCtrl" ng-controller="UserSearchListCtrl">
    <input type="hidden" value="<?php echo Url::toRoute(["profile/index", 'id' => "replaceid"]); ?>" id="user-profile-view-base-uri" />
	<div class="nav-top">
		<div class="nav-search pull-left">
			<form class="nav-search-area">
				<input type="text" id="users-search" placeholder="Search for friends" ng-model="searchQuery" ng-change="searchChanged()" ng-model-options="{ debounce: 500 }" />
			</form>
		</div>
	</div>
	
    <div class="vertical-middle" id="users-list-no-items" style="display: none;">
    	<h1>No users found</h1>
    </div>
    <div class="ajax-content-loading" id="users-list-loading" style="display: none;">
      <?php echo Html::img('@web/img/loading-big.gif') ?>
  	  Loading ...
    </div>
    
	<div class="hidden" id="users-search-items">
	    <?php /*
    	<div class="col-xs-6">
    		<div class="event clearfix m-b-20">
    			<div class="event-img pull-left"></div>
    			<div class="event-description pull-left">
    				<div class="event-title"><span>{{user.firstname}} {{user.lastname}}</span></a></div>
    				<div class="event-when">Last visit: {{user.last_login}}</a></div>
    				<div class="event-what">Rank: {{user.rank}}</div>
    			</div>
    		</div>
    	</div>
    	*/ ?>
    	
		<div class="col-xs-6" ng-repeat="user in users">
			<div class="challenge-place">
				<?php echo Html::img('@web/img/man.jpg', ['class' => 'img-circle']) ?>
				<div class="challenge-title"><a href="{{user.uri}}">{{user.firstname}} {{user.lastname}}</a></div>
				<div class="challenge-height pull-right">
					<a href="#" ng-class="isWatchingClass(user)" ng-bind="isWatchingText(user)" ng-click="toggleWatching( $event, 'list', user )" data-state="0" data-id="0" data-user-id="0">-</a>
				</div>
			</div>
		</div>
    	
	</div>
	
	<?php /*
	<div class="col-xs-6">
		<div class="event clearfix m-b-20">
			<div class="event-img pull-left"><?php echo Html::img('@web/img/man.jpg', ['class' => 'img-circle']) ?></div>
			<div class="event-description pull-left">
				<div class="event-title"><span>Iris Fivelstad</span> was out cycling</div>
				<div class="event-when">5 days ago</div>
				<div class="event-what">He tracked 33.25 km in 1h:37m:01s </div>
			</div>
		</div>
		<div class="event clearfix m-b-20">
			<div class="event-img pull-left"><?php echo Html::img('@web/img/lady2.jpg', ['class' => 'img-circle']) ?></div>
			<div class="event-description pull-left">
				<div class="event-title"><span>Iris Fivelstad</span> was out running</div>
				<div class="event-when">2 days ago</div>
				<div class="event-what">She tracked 7.92 km in 57m:22s </div>
				<div class="event-map"><?php echo Html::img('@web/img/map.jpg', ['class' => 'img-responsive']) ?></div>
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="event clearfix m-b-20">
			<div class="event-img pull-left"><?php echo Html::img('@web/img/lady2.jpg', ['class' => 'img-circle']) ?></div>
			<div class="event-description pull-left">
				<div class="event-title"><span>Iris Fivelstad</span> was out running</div>
				<div class="event-when">2 days ago</div>
				<div class="event-what">She tracked 7.92 km in 57m:22s </div>
				<div class="event-map"><?php echo Html::img('@web/img/map.jpg', ['class' => 'img-responsive']) ?></div>
			</div>
		</div>
	</div>
	*/ ?>
	
</div>
