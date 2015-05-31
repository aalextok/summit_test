<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Overview';
$this->params['breadcrumbs'][] = false;
?>
<?php /* ?>

<h1><?= Html::encode($this->title) ?></h1>
    <td>Companies</td>
	<td><?php echo $companyCount; ?></td>
    <td>Users</td>
    <td><?php echo $userCount; ?></td>
    <td>Customers</td>
    <td><?php echo $customerCount; ?></td>
    <td>Cards</td>
    <td><?php echo $cardCount; ?></td>
*/ ?>

<div class="b-dashboard">
    <div class="dashboard-info-cards">
        <ul>
            <li class="dashboard-info-card">
                <div class="num">
                    <?php echo $cardCount; ?>
                </div>Cards in Deck
            </li>
            <li class="dashboard-info-card">
                <div class="num">
                    <?php echo $customerCount; ?>
                </div>Total users
            </li>
            <li class="dashboard-info-card">
                <div class="num">7.8</div>NPS
            </li>
            <li class="dashboard-info-card">
                <div class="num">100%</div>of cards have 5+ answers
            </li>
            <li class="dashboard-info-card">
                <div class="num">100%</div>of Сards have 5+ answers
            </li>
        </ul>
    </div>
    <div class="dashboard-main clr">
	<?php $i = 0; ?>
	<?php foreach ($cards AS $card) : ?>
        <div class="dashboard-item b-send-picture <?php if ($i == 0) : ?>fleft<?php else : ?>fright<?php endif; ?>">
            <h2><?php echo $card['title']; ?></h2>
			<?php
			switch($card['type']) {
				case "image": 
					if (!empty($card['content'])) {
			?>
					<div class="send-pictures-list">
					<?php foreach ($card['content'] AS $content) { ?>
							<div class="send-picture-item">
								<a href="#"><img src="userfiles/<?php echo $content['data']; ?>" alt="<?php echo $content['title']; ?>"></a>
							</div>
					<?php } ?>
					</div>
			<?php
					}
					break;
				default:
			?>
					<div class="experience-list">
					<?php if (!empty($card['content'])) { ?>
						<ul>
						<?php foreach ($card['content'] AS $content) { ?>
							<li class="experience-item">
								<a href="#">
									<div class="experience-name"><?php echo $content['title']; ?></div>
									<div class="experience-content">
										<p><?php echo $content['data']; ?></p>
									</div>
								</a>
							</li>
						<?php } ?>
						</ul>
					<?php } ?>
					</div>
			<?php } ?>
			<?php if ($card['total']) : ?>
            <div class="see-all-link">
				<a href="#">See all <?php echo $card['total']; ?></a>
            </div>
			<?php endif; ?>
        </div>
		<?php $i++; ?>
		<?php if ($i > 1) { break; } ?>
	<?php endforeach; ?>
		
        <div class="dashboard-item b-users-graph">
            <h2>Users who signed up in last 14 days</h2>
            <div class="users-graph-list">
                <figure class="users-graph-item">
                    <div class="users-graph-item-graphics">
                        <div style="height:75%" class="graphic g1"></div>
                        <div style="height:85%" class="graphic g2"></div>
                    </div>
                    <figcaption class="users-graph-item-title">
                        <div class="users-graph-item-percent">85</div><span>Downloads</span>
                    </figcaption>
                </figure>
                <figure class="users-graph-item">
                    <div class="users-graph-item-graphics">
                        <div style="height:70%" class="graphic g1"></div>
                        <div style="height:80%" class="graphic g2"></div>
                    </div>
                    <figcaption class="users-graph-item-title">
                        <div class="users-graph-item-percent">80</div><span>Users answered cards</span>
                    </figcaption>
                </figure>
                <figure class="users-graph-item">
                    <div class="users-graph-item-graphics">
                        <div style="height:24%" class="graphic g1"></div>
                        <div style="height:34%" class="graphic g2"></div>
                    </div>
                    <figcaption class="users-graph-item-title">
                        <div class="users-graph-item-percent">34</div><span>Full profiles</span>
                    </figcaption>
                </figure>
                <figure class="users-graph-item">
                    <div class="users-graph-item-graphics">
                        <div style="height:8%" class="graphic g1"></div>
                        <div style="height:12%" class="graphic g2"></div>
                    </div>
                    <figcaption class="users-graph-item-title">
                        <div class="users-graph-item-percent">12</div><span>Claimed rewards</span>
                    </figcaption>
                </figure>
                <figure class="users-graph-item">
                    <div class="users-graph-item-graphics">
                        <div style="height:1%" class="graphic g1"></div>
                        <div style="height:3%" class="graphic g2"></div>
                    </div>
                    <figcaption class="users-graph-item-title">
                        <div class="users-graph-item-percent">3</div><span>Sent invites</span>
                    </figcaption>
                </figure>
            </div>
        </div>
    </div>
</div>