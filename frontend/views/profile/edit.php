<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;

use kartik\widgets\FileInput;

$profilePhoto = User::getUserPhoto( $user->id );

/* @var $this yii\web\View */
?>
<div class="row" ng-controller="ProfileEditCtrl">
  <form>
    <input type="hidden" value="<?php echo $id; ?>" id="user-profile-edit-user-id" />
	<div class="profile-top">
    	
    	
    	<a href="#" id="change-profile-photo">
    	  <img src="<?php echo User::getUserPhoto( $user, true ); ?>" class="img-circle" data-image-id="<?php echo isset($profilePhoto['id']) ? $profilePhoto['id'] : 0; ?>" />
    	</a>
    	
		<div class="profile-name clearfix"><?php echo User::getUserDisplayName( $user ); ?></div>
		<div class="profile-stats clearfix">
			<div class="friends pull-left">FRIENDS <br><span><?php echo User::getUserFriendCount( $user->id ); ?></span></div>
			<div class="meters pull-right">METERS <br><span><?php echo $user->meters_above_sea_level; ?></span></div>
		</div>
		<div class="profile-save pull-right">
			<a href="#" ng-click="proccessForm( $event )">Save</a>
		</div>
	</div>
	
	<div class="col-xs-12 feedbacks">
    	<div class="alert alert-success hidden">
    	  Profile updated.
    	</div>
    	<div class="alert alert-danger hidden" data-original="Profile update failed. Try again. " data-password-error="Password update failed">
    	  Profile update failed. Try again. 
    	</div>
        <div class="ajax-content-loading hidden">
          <?php echo Html::img('@web/img/loading-big.gif') ?>
      	  Loading ...
        </div>
	</div>
	
	<div class="col-xs-12 profile-image-updater">
        <?php
        $initialPreview = [];
        $initialPreviewConfig = [];
        
        /*
        foreach($user->images as $image){
            $initialPreview[] = Html::img(Yii::getAlias('@web').'/'.$image->location, ['class'=>'file-preview-image', 'alt' => $image->name, 'title' => $image->name]);
        
            $initialPreviewConfig[] = [
                'caption' => $image->name,
                'url' => Url::to(['site/delete-image', 'id' => $image->id]),
                'key' => $image->id,
            ];
        }
        */
        
        $authToken = Yii::$app->user->isGuest ? "" : Yii::$app->user->identity->getAuthKey();
        $uri = $this->context->getApiBaseUri();
        
        $uri .= '/image/create';
        
        $ajaxSettings = new stdClass();
        $ajaxSettings->headers = new stdClass();
        
        $ajaxSettings->headers->Authorization = 'Bearer ' . $authToken;
        $ajaxSettings->headers->Accept = 'application/json;odata=verbose';
        
        //https://github.com/kartik-v/bootstrap-fileinput
        echo FileInput::widget([
            'name' => 'image',
            'pluginEvents' => [
              "fileuploaded" => 'function(event, data, previewId, index) { afterUploadPhoto(event, data);  }',
              "fileuploaderror" => 'function(event, data, previewId, index){  }',
              "fileloaded" => 'function(event, data, previewId, index) { autoUploadPhoto(event, data);  }',
            ],
            'options'=>[
                'multiple' => false,
                'accept' => 'image/*',
                'class' => 'ajax-uploader-btn',
            ],
            'pluginOptions' => [
                'uploadUrl' => $uri,  
                'dropZoneEnabled' => false,           
                'uploadExtraData' => [
                  'model' => 'User',
                  'model_id' => $user->id,
                  'is_avatar' => '1',
                ],
                'ajaxSettings' => $ajaxSettings,
                'initialPreview' => $initialPreview,
                'initialPreviewConfig' => $initialPreviewConfig,
                'overwriteInitial' => false,
                'maxFileCount' => 1
            ],
        ]);
        
        ?>
	</div>
	
	<div class="col-xs-6">
		<div class="one-field clearfix">
			<div class="label-profile pull-left">Firstname</div>
			<div class="input-profile pull-left"><input type="text" class="form-control" id="profileName" ng-init="formData.firstname='<?php echo $user->firstname; ?>'" value="<?php echo $user->firstname; ?>" ng-model="formData.firstname"></div>
		</div>
		<div class="one-field clearfix">
			<div class="label-profile pull-left">Lastname</div>
			<div class="input-profile pull-left"><input type="text" class="form-control" id="profileName" ng-init="formData.lastname='<?php echo $user->lastname; ?>'" value="<?php echo $user->lastname; ?>" ng-model="formData.lastname"></div>
		</div>
		<div class="one-field clearfix">
			<div class="label-profile pull-left">Userame</div>
			<div class="input-profile pull-left"><input type="text" class="form-control" id="profileUsername" ng-init="formData.username='<?php echo $user->username; ?>'" value="<?php echo $user->username; ?>" ng-model="formData.username"></div>
		</div>
		<div class="one-field clearfix">
			<div class="label-profile pull-left">E-mail</div>
			<div class="input-profile pull-left"><input type="text" class="form-control" id="profileEmail" value="<?php echo $user->email; ?>" disabled="disabled"></div>
		</div>
		<div class="one-field clearfix">
			<div class="label-profile pull-left">Phone</div>
			<div class="input-profile pull-left"><input type="text" class="form-control" id="profilePhone" ng-init="formData.phone='<?php echo $user->phone; ?>'" value="<?php echo $user->phone; ?>" ng-model="formData.phone"></div>
		</div>
	</div>
	
	<div class="col-xs-6">
		<div class="change-pass-title">Change password</div>
		<div class="one-field m-b-35 clearfix">
			<div class="label-profile p-10 pull-left">Current<br>password</div>
			<div class="input-profile pull-left"><input type="password" name="password" class="form-control" ng-model="formData.password" value=""></div>
		</div>
		<div class="one-field clearfix">
			<div class="label-profile np-p pull-left">New password</div>
			<div class="input-profile pull-left"><input name="new_password" type="password" class="form-control" ng-model="formData.new_password" ></div>
		</div>
		<div class="one-field clearfix">
			<div class="label-profile p-10 pull-left">New password, again</div>
			<div class="input-profile pull-left"><input name="new_password_repeat" type="password" class="form-control" ng-model="formData.new_password_repeat" ></div>
		</div>
	</div>
  </form>
</div>