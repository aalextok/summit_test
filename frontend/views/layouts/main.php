<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;


/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$contentClasses = $this->context->getClasses("content", "challenges");
$userDisplayName = $this->context->getUserDisplayName();



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
</head>
<body>
    <?php $this->beginBody() ?>
    
    <div class="wrapper">
      <div class="column left-col" id="sidebar">
		<a class="logo" href="<?php echo Url::toRoute("site/index"); ?>">
		  <?php echo Html::img('@web/img/logo-min.png') ?>
		</a>
		<div class="ins-code hi-icon-effect-5 hi-icon-effect-5a"><a href="<?php echo Url::toRoute("place/checkin"); ?>" class="hi-icon icon-plus icon-green-o plus-o m-l-n-100"><span>Insert code</span></a></div>
		<ul class="nav">
			<li><a href="<?php echo Url::toRoute("site/index"); ?>" class="active">Main feed</a></li>
			<li><a href="<?php echo Url::toRoute("friend/index"); ?>">Friends</a></li>
			<li><a href="<?php echo Url::toRoute("profile/index"); ?>">My profile</a></li>
			<li><a href="<?php echo Url::toRoute("competition/index"); ?>">Challenges</a></li>
			<li class="small-links first-link"><a href="<?php echo Url::toRoute("profile/settings"); ?>">Settings</a></li>
			<li class="small-links"><a href="<?php echo Url::toRoute("site/about"); ?>">About</a></li>
		</ul>
		
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
