<?php
use yii\helpers\Html;
use yii\widgets\Menu;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use common\models\User;


/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$contentClasses = $this->context->getClasses("content", "challenges");
$userDisplayName = $this->context->getCurrentUserDisplayName();
$userId = User::getCurrentUserId();
$userId = ($userId === null) ? 0 : $userId;
$authToken = Yii::$app->user->isGuest ? "" : Yii::$app->user->identity->getAuthKey();

?><?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" ng-app="stsApp">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
      var stoFrontendBaseUri = "<?php echo Url::base(); ?>";
      var stoApiBaseUri = "<?php echo $this->context->getApiBaseUri(); ?>";
      var stoAuthToken = "<?php echo $authToken; /* TODO: store in cookie for javascript? */ ?>";
    </script>  
</head>
<body>
    <div id="fb-root"></div>
    <?php $this->beginBody() ?>
    
    <div class="wrapper">
      <div class="column left-col" id="sidebar">
		<a class="logo" href="<?php echo Url::toRoute("site/index"); ?>">
		  <?php echo Html::img('@web/img/logo-min.png') ?>
		</a>
		
        <?php  if (!Yii::$app->user->isGuest) { ?>
  		  <div class="ins-code hi-icon-effect-5 hi-icon-effect-5a"><a href="<?php echo Url::toRoute("place/visit"); ?>" class="hi-icon icon-plus icon-green-o plus-o m-l-n-100"><span>Insert code</span></a></div>
		<?php } ?>
		 
		<?php
		  //TODO: get all active states to work in menu
          echo Menu::widget([
              'items' => [
                ['label' => 'Main feed', 'url' => ['site/dashboard'], 'visible' => !Yii::$app->user->isGuest],
          		['label' => 'Friends', 'url' => ['friend/index'], 'visible' => !Yii::$app->user->isGuest],
          		[
                  'label' => 'My profile', 
                  'url' => Url::to(['profile/index', 'id' => $userId]), 
                  'visible'=>!Yii::$app->user->isGuest, 
                  'active' => $this->context->stoMenuItemActive('my-profile')
                ],
          		['label' => 'Challenges', 'url' => ['competition/index']],
          		/*[
                  'label' => 'Settings', 
                  'url' => ['profile/settings'], 
                  'template' => '<li class="small-links first-link ' . ($this->context->stoMenuItemActive('my-settings') ? 'active' : '') . '"><a href="{url}">{label}</a></li>', 
                  'visible'=>!Yii::$app->user->isGuest
                ],*/
          		[
                  'label' => 'About', 
                  'url' => Url::to(['site/about']), 
                  'template' => '<li class="small-links first-link ' . ($this->context->stoMenuItemActive('about') ? 'active' : '') . '"><a href="{url}">{label}</a></li>'
                ],
              ],
              'options' => [
                'class' => 'nav',
              ],
          	'activeCssClass'=>'active',
          ]);
        ?>
		
		<div id="sidebar-footer">
		    <?php  if (Yii::$app->user->isGuest) { ?>
              
            <?php } else { ?>
    			<div class="logged">Logged in, <span><?php echo $userDisplayName; ?></span></div>
    			<a href="<?php echo Url::toRoute("site/logout"); ?>">Log out</a>
            <?php } ?>
		</div>
	  </div>
    
    
	<div class="column right-col <?php echo $contentClasses; ?>" id="main">
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
