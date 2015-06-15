<?php
use yii\helpers\Html;
use common\models\User;

/* @var $this yii\web\View */
?>
<div class="row">
  <form>
	<div class="profile-top">
		<?php echo Html::img('@web/img/lady.jpg', ['class' => 'img-circle']) ?>
		<div class="profile-name clearfix"><?php echo User::getUserDisplayName( $user ); ?></div>
		<div class="profile-stats clearfix">
			<div class="friends pull-left">FRIENDS <br><span><?php echo User::getUserFriendCount( $user->id ); ?></span></div>
			<div class="meters pull-right">METERS <br><span><?php echo $user->meters_above_sea_level; ?></span></div>
		</div>
		<div class="profile-save pull-right">
			<a href="#">Save</a>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="one-field clearfix">
			<div class="label-profile pull-left">Name</div>
			<div class="input-profile pull-left"><input type="text" class="form-control" id="profileName" value="Iris Fivelstad"></div>
		</div>
		<div class="one-field clearfix">
			<div class="label-profile pull-left">Userame</div>
			<div class="input-profile pull-left"><input type="text" class="form-control" id="profileUsername" value="Iris_F"></div>
		</div>
		<div class="one-field clearfix">
			<div class="label-profile pull-left">E-mail</div>
			<div class="input-profile pull-left"><input type="text" class="form-control" id="profileEmail" value="iris@gmail.com"></div>
		</div>
		<div class="one-field clearfix">
			<div class="label-profile pull-left">Phone</div>
			<div class="input-profile pull-left"><input type="text" class="form-control" id="profilePhone" value="55 5555 55"></div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="change-pass-title">Change password</div>
		<div class="one-field m-b-35 clearfix">
			<div class="label-profile p-10 pull-left">Current<br>password</div>
			<div class="input-profile pull-left"><input type="password" class="form-control" id="profileName" value="demopass"></div>
		</div>
		<div class="one-field clearfix">
			<div class="label-profile np-p pull-left">New password</div>
			<div class="input-profile pull-left"><input type="password" class="form-control" id="profileName"></div>
		</div>
		<div class="one-field clearfix">
			<div class="label-profile p-10 pull-left">New password, again</div>
			<div class="input-profile pull-left"><input type="password" class="form-control" id="profileName"></div>
		</div>
	</div>
  </form>
</div>