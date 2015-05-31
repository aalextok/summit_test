<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AdminAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width = device-width">
    <?= Html::csrfMetaTags() ?>
    <title>The Board | <?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- <link rel='shortcut icon' href='favicon.ico'>-->
</head>
<body>
    <?php $this->beginBody() ?>
        <div id="wrapper">
            <header class="clr"><a href="<?php echo Url::to(['admin/index']); ?>" class="logo"><?= Html::img('@web/images/logo@2x.png', array('width' => 131, 'height' => 39, 'alt' => 'The Board')); ?></a>
                <h1>The Board</h1>
				<?php if (!\Yii::$app->user->isGuest) { ?>
					<div class="header-panel">
						<div class="panel-item header-search">
							<div class="form-item">
								<input type="text" name="search" class="form-text">
								<button type="submit" class="form-submit fa fa-search"></button>
							</div>
						</div>
						<div class="panel-item"><a href="<?php echo Url::to(['admin/index']); ?>" class="account-link fa fa-cog"><span>Account</span></a></div>
						<div class="panel-item"><a href="<?php echo Url::to(['site/logout']); ?>" class="logout-link fa fa-times"><span>Log Out <?php echo ' (' . Yii::$app->user->identity->username . ')'; ?></span></a></div>
					</div>
				<?php } ?>
            </header>
            <div class="main">
				
				<?php if (!\Yii::$app->user->isGuest) { ?>
                <aside class="nav-panel">
                    <nav class="site-nav">
                        <?php
                            echo Nav::widget([						
                                'items' => [
                                    [
                                        'label'		=> '<span>Dashboard</span>',
                                        'linkOptions'	=> ['class' => 'fa fa-th'],								
                                        'url'		=> Url::to(['admin/index'])
                                    ],
									/*									
                                    [
                                        'label'		=> '<span>Results</span>',
										'options'	=> ['class' => 'with-subnav'],
										'linkOptions'	=> ['class' => 'fa fa-bar-chart'],
										'url'			=> Url::to(['admin/index'])
									],	
									*/
									[
										'label'		=> '<span>Cards</span>',
										'options'	=> ['class' => 'with-subnav'],
										'linkOptions'	=> ['class' => 'fa fa-files-o'],
										'url'			=> Url::to(['admin/card/index'])
									],
									[
										'label'		=> '<span>Customers</span>',
										'options'	=> ['class' => 'with-subnav'],
										'linkOptions'	=> ['class' => 'fa fa-user'],
										'url'			=> Url::to(['admin/customer/index']),
										'visible'	=> ($user->is_superadmin === 1)
									],
									[
										'label'		=> '<span>Users</span>',
										'options'	=> ['class' => 'with-subnav'],
										'linkOptions'	=> ['class' => 'fa fa-user'],
										'url'			=> Url::to(['admin/user/index']),
										'visible'	=> ($user->is_superadmin === 1)
									],
									[
										'label'		=> '<span>Companies</span>',
										'options'	=> ['class' => 'with-subnav'],
										'linkOptions'	=> ['class' => 'fa fa-user'],
										'url'		=> Url::to(['admin/company/index']),
										'visible'	=> ($user->is_superadmin === 1)
									],
								],
								'options'			=> ['class' => ''],
								'activateParents'	=> true,
								'encodeLabels'		=> false	
                            ]);
						?>
                    </nav>
				</aside>
				<?php } ?>

				<section class="main-content">
							<?php /* Breadcrumbs::widget([
								'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
					'homeLink' => ['label' => 'Home', 'url' => Url::to(['admin/index'])]
					]) */ ?>
							<?php echo $content ?>					
				</section>
            </div>
	</div>
	<footer></footer>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>