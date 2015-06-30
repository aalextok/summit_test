<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use yii\bootstrap\ActiveForm;


/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$authToken = Yii::$app->user->isGuest ? "" : Yii::$app->user->identity->getAuthKey();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
      var stoFrontendBaseUri = "<?php echo Url::base(); ?>";
      var stoApiBaseUri = "<?php echo $this->context->getApiBaseUri(); ?>";
      var stoImagesBaseUri = "<?php echo $this->context->getApiBaseUri(); ?>";
      var stoAuthToken = "<?php echo $authToken; /* TODO: store in cookie for javascript? */ ?>";
    </script>  
</head>
<body>
    <div id="fb-root"></div>
    <?php $this->beginBody() ?>
    
        <script>
          window.fbAsyncInit = function() {
            FB.init({
              appId      : '<?php echo $this->context->getFacebookAppId( ); ?>',
              xfbml      : true,
              version    : 'v2.3'
            });
          };
    
          (function(d, s, id){
             var js, fjs = d.getElementsByTagName(s)[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement(s); js.id = id;
             js.src = "//connect.facebook.net/en_US/sdk.js";
             fjs.parentNode.insertBefore(js, fjs);
           }(document, 'script', 'facebook-jssdk'));
        </script>
        
		<div class="site-wrapper">
			<div class="site-wrapper-inner">
				<div class="cover-container">
					<div class="logo">
					    <a href="<?php echo Url::toRoute("site/index"); ?>">
						  <?php echo Html::img('@web/img/logo.png') ?>
						</a>
					</div>
					
                    <?= Alert::widget() ?>
                    <?= $content ?>
                        
				</div>
				<div class="apps">
					<span>Or download our app</span>
					<div class="apple-app">
						<a href="?">
						  <?php echo Html::img('@web/img/btn-apple.png', ['style' => 'width: 130px;']) ?>
						</a>
					</div>
					<div class="android-app">
						<a href="?">
						  <?php echo Html::img('@web/img/btn-android.png', ['style' => 'width: 130px;']) ?>
						</a>
					</div>
				</div>
			</div>
		</div>
    

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
