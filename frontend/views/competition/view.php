<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
?>

 <input type="hidden" value="background:url(<?php echo Url::toRoute("img/bg-gray-challenge.png"); ?>) no-repeat;background-position: center center;background-size: cover;" id="main-style-replace" />

<div class="row">
  <div class="col-xs-12 back-link">
    <a href="<?php echo Url::toRoute(["competition/index"]); ?>">
      <?php echo Html::img('@web/img/back-arrow.png', ['class' => 'm-r-15', 'style' => 'height: 40px;']) ?>Back
    </a> 
    <h1><?php echo $competition->name; ?></h1>
  </div>
<div class="col-xs-6">
	<div class="place-item">
		<div class="challenge-place clearfix">
			<div class="challenge-image pull-left">
				<?php echo Html::img('@web/img/mountain.jpg', ['class' => 'img-circle']) ?>
				<?php echo Html::img('@web/img/done-icon.png', ['class' => 'done', 'style' => 'width: 34px;']) ?>
			</div>
			<div class="challenge-title pull-left"><a href="#" onclick="Show_Div(Div_1)">Jatka</a></div>
			<div class="challenge-height pull-right"><span>1588</span> Meters above <br>sealevel</div>
		</div>
		<div class="challenge-menu clearfix" id="Div_1" style="display: none;">
			<div class="challenge-add-code pull-left">
				<a href="<?php echo Url::toRoute("place/visit"); ?>">
				  <?php echo Html::img('@web/img/plus-icon-white.png', ['class' => 'm-r-15', 'style' => 'width: 35px;']) ?>
			      Add code
			    </a>
			</div>
			<div class="challenge-more pull-right">
			    <a href="<?php echo Url::toRoute(["place/view", 'id' => 1]); ?>">
				  <?php echo Html::img('@web/img/info-icon.png', ['class' => 'm-l-15', 'style' => 'width: 35px;']) ?>
				</a>
			</div>
		</div>
	</div>
	<div class="place-item">
		<div class="challenge-place clearfix">
			<div class="challenge-image pull-left">
				<?php echo Html::img('@web/img/mountain.jpg', ['class' => 'img-circle']) ?>
			</div>
			<div class="challenge-title pull-left"><a href="#" onclick="Show_Div(Div_2)">Jatka</a></div>
			<div class="challenge-height pull-right"><span>1588</span> Meters above <br>sealevel</div>
		</div>
		<div class="challenge-menu clearfix" id="Div_2" style="display:none;">
			<div class="challenge-add-code pull-left">
				<a href="<?php echo Url::toRoute("place/visit"); ?>">
				  <?php echo Html::img('@web/img/plus-icon-white.png', ['class' => 'm-r-15', 'style' => 'width: 35px;']) ?>
			      Add code
			    </a>
			</div>
			<div class="challenge-more pull-right">
			    <a href="<?php echo Url::toRoute(["place/view", 'id' => 1]); ?>">
				  <?php echo Html::img('@web/img/info-icon.png', ['class' => 'm-l-15', 'style' => 'width: 35px;']) ?>
				</a>
			</div>
		</div>
	</div>
	<div class="place-item">
		<div class="challenge-place">
			<?php echo Html::img('@web/img/mountain.jpg', ['class' => 'img-circle']) ?>
			<div class="challenge-title"><a href="#" onclick="Show_Div(Div_5)">Jatka</a></div>
			<div class="challenge-height pull-right"><span>1588</span> Meters above <br>sealevel</div>
		</div>
		<div class="challenge-menu clearfix" id="Div_5">
			<div class="challenge-add-code pull-left">
				<a href="<?php echo Url::toRoute("place/visit"); ?>">
				  <?php echo Html::img('@web/img/plus-icon-white.png', ['class' => 'm-r-15', 'style' => 'width: 35px;']) ?>
			      Add code
			    </a>
			</div>
			<div class="challenge-more pull-right">
    		    <a href="<?php echo Url::toRoute(["place/view", 'id' => 1]); ?>">
    			  <?php echo Html::img('@web/img/info-icon.png', ['class' => 'm-l-15', 'style' => 'width: 35px;']) ?>
    			</a>
			</div>
		</div>
	</div>
</div>
<div class="col-xs-6">
	<div class="place-item">
		<div class="challenge-place clearfix">
			<div class="challenge-image pull-left">
				<?php echo Html::img('@web/img/mountain.jpg', ['class' => 'img-circle']) ?>
			</div>
			<div class="challenge-title pull-left"><a href="#" onclick="Show_Div(Div_3)">Jatka</a></div>
			<div class="challenge-height pull-right"><span>1588</span> Meters above <br>sealevel</div>
		</div>
		<div class="challenge-menu clearfix" id="Div_3" style="display:none;">
			<div class="challenge-add-code pull-left">
				<a href="<?php echo Url::toRoute("place/visit"); ?>">
				  <?php echo Html::img('@web/img/plus-icon-white.png', ['class' => 'm-r-15', 'style' => 'width: 35px;']) ?>
			      Add code
			    </a>
			</div>
			<div class="challenge-more pull-right">
			    <a href="#">
				  <?php echo Html::img('@web/img/info-icon.png', ['class' => 'm-l-15', 'style' => 'width: 35px;']) ?>
				</a>
			</div>
		</div>
	</div>
	<div class="place-item">
		<div class="challenge-place clearfix">
			<div class="challenge-image pull-left">
				<?php echo Html::img('@web/img/mountain.jpg', ['class' => 'img-circle']) ?>
				<img src="img/done-icon.png" width="34px" class="done">
			</div>
			<div class="challenge-title pull-left"><a href="#" onclick="Show_Div(Div_4)">Jatka</a></div>
			<div class="challenge-height pull-right"><span>1588</span> Meters above <br>sealevel</div>
		</div>
		<div class="challenge-menu clearfix" id="Div_4" style="display:none;">
				<div class="challenge-add-code pull-left">
    				<a href="<?php echo Url::toRoute("place/visit"); ?>">
    				  <?php echo Html::img('@web/img/plus-icon-white.png', ['class' => 'm-r-15', 'style' => 'width: 35px;']) ?>
    			      Add code
    			    </a>
				</div>
				<div class="challenge-more pull-right">
    			    <a href="#">
    				  <?php echo Html::img('@web/img/info-icon.png', ['class' => 'm-l-15', 'style' => 'width: 35px;']) ?>
    				</a>
				</div>
			</div>
		</div>
	</div>
</div>

