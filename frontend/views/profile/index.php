<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;

$currentUserId = User::getCurrentUserId();
$watchingId = User::getUserFollowingId($currentUserId, $user->id);

/* @var $this yii\web\View */
?>
<div class="row">
	<div class="profile-top" ng-controller="UserProfileCtrl">
		<?php echo Html::img('@web/img/lady.jpg', ['class' => 'img-circle']) ?>
		<div class="profile-name clearfix"><?php echo User::getUserDisplayName( $user ); ?></div>
		<div class="profile-stats clearfix">
			<div class="friends pull-left">FRIENDS <br><span><?php echo User::getUserFriendCount( $user->id ); ?></span></div>
			<div class="meters pull-right">METERS <br><span><?php echo $user->meters_above_sea_level; ?></span></div>
		</div>
		
		<?php  if (!Yii::$app->user->isGuest) { ?>
		  <?php  if ($user->id == $currentUserId) { ?>
    		<div class="profile-edit pull-right">
    			<a href="<?php echo Url::to(['profile/edit']); ?>"><?php echo Html::img('@web/img/edit-profile.png') ?>edit profile</a>
    		</div>
          <?php } else { ?>
    		<div class="profile-edit challenge-height pull-right">
    		    <?php if($watchingId){ ?>
    			  <a href="#" class="unf-green user-unfollow" ng-click="toggleWatching( $event, 'view', null )" data-state="1" data-id="<?php echo $watchingId; ?>" data-user-id="<?php echo $user->id; ?>">unfollow</a>
    			<?php } else { ?>
    			  <a href="#" class="unf-green user-follow" ng-click="toggleWatching( $event, 'view', null )" data-state="0" data-id="<?php echo $user->id; ?>" data-user-id="<?php echo $user->id; ?>">follow</a>
    			<?php } ?>
    		</div>
         <?php } ?>
        <?php } ?>
		
	</div>
	
    <div class="ajax-content-loading" id="activities-list-loading" style="display: none;">
      <?php echo Html::img('@web/img/loading-big.gif') ?>
  	  Loading ...
    </div>
    
	<div class="mgrid hidden" ng-controller="UserActivitiesListingCtrl" id="activities-list" >
    	<div class="col-xs-6 mgrid-item">
    		<div class="one-field clearfix m-b-20">
    			<div class="king-label pull-left">Rank</div>
    			<div class="king-profile pull-left"><?php echo Html::img('@web/img/crown.jpg') ?> <?php echo User::getUserRankDisplay($user); ?></div>
    		</div>
  		</div>
  		
  		<?php /*
    	<div class="col-xs-6 mgrid-item">
    		<div class="event clearfix m-b-20">
    			<div class="event-img pull-left">
    			  <?php echo Html::img('@web/img/lady.jpg', ['class' => 'img-circle']) ?>
    			</div>
    			<div class="event-description pull-left">
    				<div class="event-title"><span>Iris Fivelstad</span> was out cycling</div>
    				<div class="event-when">2 days ago</div>
    				<div class="event-what">She tracked {{km}} km in 1h:37m:01s </div>
    			</div>
    		</div>
  		</div>
    	*/ ?>
    	
    	<div class="col-xs-6 mgrid-item" ng-repeat="event in events">
    		<div class="event clearfix m-b-20">
    			<div class="event-img pull-left"><?php echo Html::img('@web/img/lady.jpg', ['class' => 'img-circle']) ?></div>
    			<div class="event-description pull-left">
    				<div class="event-title"><span><?php echo User::getUserDisplayName( $user ); ?></span> was out {{event.activity.verb}}</div>
    				<div class="event-when" title="{{event.isodate}}"></div>
    				<?php /* 
    				  <div class="event-what">She tracked 7.92 km in 57m:22s </div> 
    				  <div class="event-map"><?php echo Html::img('@web/img/map.jpg', ['class' => 'img-responsive']) ?></div>
    				*/  ?>
    			</div>
    		</div>
    	</div>
    	
    	
	</div>
	
</div>
<input type="hidden" value="<?php echo $user->id; ?>" id="user-view-id" />
