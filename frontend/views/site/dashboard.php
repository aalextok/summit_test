<?php
/* @var $this yii\web\View */
$this->title = 'Welcome';
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
					<?php foreach($allActivities as $k => $activity){ ?>
					  <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><?php echo $activity->name; ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
		
		<div class="nav-activities pull-left">
			<div class="dropdown">
				<a class="btn-menu menu-link dropdown-toggle" type="button" id="locationMenu" data-toggle="dropdown" aria-expanded="true">
				<span class="pull-left">Location</span>
				  <?php echo Html::img('@web/img/dropdown.png', ['class' => 'pull-right', 'style' => 'height: 22px;']) ?>
				</a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="locationMenu">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Place 1</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Place 2</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Place 3</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Place 4</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Place 5</a></li>
				</ul>
			</div>
		</div>
		<div class="nav-map pull-right">
			<a href="#">
			  <?php echo Html::img('@web/img/map-icon.png', ['style' => 'height: 22px;']) ?>
			</a>
		</div>
	</div>
	<div class="col-xs-6 m-b-40">
		<div class="event clearfix m-0 b-r-top">
			<div class="event-img pull-left"><?php echo Html::img('@web/img/hike.jpg', ['class' => 'img-circle']) ?></div>
			<div class="event-description pull-left">
				<div class="event-title">Hike to Toonoja swamp islands</div>
				<div class="event-when">Toonoja is an ancient island in the largest swamp in Estonia that was first inhabited 4000 years ago
				</div>
			</div>
		</div>
		<div class="green-bottom clearfix">
			<div class="place-loc pull-left">Karuskose, Sandra k����la, Suure-Jaani vald, Viljandi makond</div>
			<div class="place-more pull-right">
				<a href="#">
				  <?php echo Html::img('@web/img/arrow-right.png', ['style' => 'height: 22px;']) ?>
				</a>
			</div>
		</div>
	</div>
	<div class="col-xs-6 m-b-40">
		<div class="event clearfix m-0 b-r-top">
			<div class="event-img pull-left"><?php echo Html::img('@web/img/hike.jpg', ['class' => 'img-circle']) ?></div>
			<div class="event-description pull-left">
				<div class="event-title">Hike to Toonoja swamp islands</div>
				<div class="event-when">Toonoja is an ancient island in the largest swamp in Estonia that was first inhabited 4000 years ago</div>
			</div>
		</div>
		<div class="green-bottom clearfix">
			<div class="place-loc pull-left">Karuskose, Sandra k������la, Suure-Jaani vald, Viljandi makond</div>
			<div class="place-more pull-right">
				<a href="#">
				  <?php echo Html::img('@web/img/arrow-right.png', ['style' => 'height: 22px;']) ?>
				</a>
			</div>
		</div>
	</div>
	<div class="col-xs-6 m-b-40">
		<div class="event clearfix m-0 b-r-top">
			<div class="event-img pull-left"><?php echo Html::img('@web/img/hike.jpg', ['class' => 'img-circle']) ?></div>
			<div class="event-description pull-left">
				<div class="event-title">Hike to Toonoja swamp islands</div>
				<div class="event-when">Toonoja is an ancient island in the largest swamp in Estonia that was first inhabited 4000 years ago</div>
			</div>
		</div>
		<div class="green-bottom clearfix">
			<div class="place-loc pull-left">Karuskose, Sandra k������la, Suure-Jaani vald, Viljandi makond</div>
			<div class="place-more pull-right">
				<a href="#">
				  <?php echo Html::img('@web/img/arrow-right.png', ['style' => 'height: 22px;']) ?>
				</a>
			</div>
		</div>
	</div>
	<div class="col-xs-6 m-b-40">
		<div class="event clearfix m-0 b-r-top">
			<div class="event-img pull-left"><?php echo Html::img('@web/img/hike.jpg', ['class' => 'img-circle']) ?></div>
			<div class="event-description pull-left">
				<div class="event-title">Hike to Toonoja swamp islands</div>
				<div class="event-when">Toonoja is an ancient island in the largest swamp in Estonia that was first inhabited 4000 years ago</div>
			</div>
		</div>
		<div class="green-bottom clearfix">
			<div class="place-loc pull-left">Karuskose, Sandra k������la, Suure-Jaani vald, Viljandi makond</div>
			<div class="place-more pull-right">
				<a href="#">
				  <?php echo Html::img('@web/img/arrow-right.png', ['style' => 'height: 22px;']) ?>
				</a>
			</div>
		</div>
	</div>
	<div class="col-xs-6 m-b-40">
		<div class="event clearfix m-0 b-r-top">
			<div class="event-img pull-left"><?php echo Html::img('@web/img/hike.jpg', ['class' => 'img-circle']) ?></div>
			<div class="event-description pull-left">
				<div class="event-title">Hike to Toonoja swamp islands</div>
				<div class="event-when">Toonoja is an ancient island in the largest swamp in Estonia that was first inhabited 4000 years ago</div>
			</div>
		</div>
		<div class="green-bottom clearfix">
			<div class="place-loc pull-left">Karuskose, Sandra k������la, Suure-Jaani vald, Viljandi makond</div>
			<div class="place-more pull-right">
				<a href="#">
				  <?php echo Html::img('@web/img/arrow-right.png', ['style' => 'height: 22px;']) ?>
				</a>
			</div>
		</div>
	</div>
</div>