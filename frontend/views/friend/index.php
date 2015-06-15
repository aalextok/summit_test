<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
?>
<div class="row">
	<div class="nav-top">
		<div class="nav-activities pull-left">
			<div class="dropdown">
				<a class="btn-menu menu-link dropdown-toggle" type="button" id="activitiesMenu" data-toggle="dropdown" aria-expanded="true">
				<span class="pull-left">All activities</span>
				  <?php echo Html::img('@web/img/dropdown.png', ['class' => 'pull-right', 'style' => 'height: 22px;']) ?>
				</a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="activitiesMenu">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">All activities</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Hiking</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Kayaking</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Running</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Sailing</a></li>
				</ul>
			</div>
		</div>
		<div class="nav-search pull-left">
			<form class="nav-search-area">
				<input type="text" id="input" placeholder="Search for friends" />
			</form>
		</div>
		<div class="nav-map pull-right">
			<a href="#">
		      <?php echo Html::img('@web/img/map-icon.png', ['style' => 'height: 22px;']) ?>
			</a>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="event clearfix m-b-20">
			<div class="event-img pull-left"><?php echo Html::img('@web/img/man.jpg', ['class' => 'img-circle']) ?></div>
			<div class="event-description pull-left">
				<div class="event-title"><span>Iris Fivelstad</span> was out cycling</div>
				<div class="event-when">5 days ago</div>
				<div class="event-what">He tracked 33.25 km in 1h:37m:01s </div>
			</div>
		</div>
		<div class="event clearfix m-b-20">
			<div class="event-img pull-left"><?php echo Html::img('@web/img/lady2.jpg', ['class' => 'img-circle']) ?></div>
			<div class="event-description pull-left">
				<div class="event-title"><span>Iris Fivelstad</span> was out running</div>
				<div class="event-when">2 days ago</div>
				<div class="event-what">She tracked 7.92 km in 57m:22s </div>
				<div class="event-map"><?php echo Html::img('@web/img/map.jpg', ['class' => 'img-responsive']) ?></div>
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="event clearfix m-b-20">
			<div class="event-img pull-left"><?php echo Html::img('@web/img/lady2.jpg', ['class' => 'img-circle']) ?></div>
			<div class="event-description pull-left">
				<div class="event-title"><span>Iris Fivelstad</span> was out running</div>
				<div class="event-when">2 days ago</div>
				<div class="event-what">She tracked 7.92 km in 57m:22s </div>
				<div class="event-map"><?php echo Html::img('@web/img/map.jpg', ['class' => 'img-responsive']) ?></div>
			</div>
		</div>
	</div>
</div>
