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
    
		<div class="site-wrapper">
			<div class="site-wrapper-inner">
				<div class="cover-container">
					<div class="logo">
					    <a href="<?php echo Url::toRoute(""); ?>">
						  <?php echo Html::img('@web/img/logo.png') ?>
						</a>
					</div>
					<div class="sign-in">Sign in</div>
					<div class="inner cover row">
						
                        <?= Alert::widget() ?>
                        <?= $content ?>
						
					</div>
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
