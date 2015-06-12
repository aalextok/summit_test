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
		<a class="logo" href="#">
		<img src="img/logo-min.png">
		</a>
		<div class="ins-code hi-icon-effect-5 hi-icon-effect-5a"><a href="<?php echo Url::toRoute("place/checkin"); ?>" class="hi-icon icon-plus icon-green-o plus-o m-l-n-100"><span>Insert code</span></a></div>
		<ul class="nav">
			<li><a href="<?php echo Url::toRoute("site/index"); ?>" class="active">Main feed</a></li>
			<li><a href="<?php echo Url::toRoute("friends/index"); ?>">Friends</a></li>
			<li><a href="<?php echo Url::toRoute("profile/index"); ?>">My profile</a></li>
			<li><a href="<?php echo Url::toRoute("competition/index"); ?>">Challenges</a></li>
			<li class="small-links first-link"><a href="<?php echo Url::toRoute("profile/settings"); ?>">Settings</a></li>
			<li class="small-links"><a href="<?php echo Url::toRoute("site/about"); ?>">About</a></li>
		</ul>
        <?php
            /*
            NavBar::begin([
                'brandLabel' => 'Summit much',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
                ['label' => 'Friends', 'url' => ['/site/friends']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                $menuItems[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => ''],
                'items' => $menuItems,
            ]);
            NavBar::end();
            */
        ?>
		
		
		<div id="sidebar-footer">
			<div class="logged">Logged in, <span>John Smith</span></div>
			<a href="#">Log out</a>
		</div>
	  </div>
    
    
	<div class="column right-col <?php echo $contentClasses; ?>" id="main">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
