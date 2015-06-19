<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;

/* @var $this yii\web\View */
?>
<div class="row">
	<div class="profile-top">
		<?php echo Html::img('@web/img/lady.jpg', ['class' => 'img-circle']) ?>
		<div class="profile-name clearfix"><?php echo User::getUserDisplayName( $user ); ?></div>
		<div class="profile-stats clearfix">
			<div class="friends pull-left">FRIENDS <br><span><?php echo User::getUserFriendCount( $user->id ); ?></span></div>
			<div class="meters pull-right">METERS <br><span><?php echo $user->meters_above_sea_level; ?></span></div>
		</div>
		<div class="profile-edit pull-right">
			<a href="<?php echo Url::to(['profile/edit']); ?>"><?php echo Html::img('@web/img/edit-profile.png') ?>edit profile</a>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="one-field clearfix m-b-20">
			<div class="king-label pull-left">Rank</div>
			<div class="king-profile pull-left"><?php echo Html::img('@web/img/crown.jpg') ?> <?php echo User::getUserRankDisplay($user); ?></div>
		</div>
		<div class="event clearfix m-b-20">
			<div class="event-img pull-left">
			  <?php echo Html::img('@web/img/lady.jpg', ['class' => 'img-circle']) ?>
			</div>
			<div class="event-description pull-left">
				<div class="event-title"><span>Iris Fivelstad</span> was out cycling</div>
				<div class="event-when">2 days ago</div>
				<div class="event-what">She tracked 33.25 km in 1h:37m:01s </div>
			</div>
		</div>
		<div class="event clearfix m-b-20">
			<div class="event-img pull-left">
			  <?php echo Html::img('@web/img/lady.jpg', ['class' => 'img-circle']) ?>
			</div>
			<div class="event-description pull-left">
				<div class="event-title"><span>Iris Fivelstad</span> was out running</div>
				<div class="event-when">2 days ago</div>
				<div class="event-what">She tracked 7.92 km in 57m:22s </div>
				<div class="event-map">
			      <?php pre($user); ?>    
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="event clearfix m-b-20">
			<div class="event-img pull-left"><?php echo Html::img('@web/img/lady.jpg', ['class' => 'img-circle']) ?></div>
			<div class="event-description pull-left">
				<div class="event-title"><span>Iris Fivelstad</span> was out running</div>
				<div class="event-when">2 days ago</div>
				<div class="event-what">She tracked 7.92 km in 57m:22s </div>
				<div class="event-map"><?php echo Html::img('@web/img/map.jpg', ['class' => 'img-responsive']) ?></div>
			</div>
		</div>
	</div>
</div>