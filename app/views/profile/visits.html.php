<?php use \app\models\User; ?>
<?php use app\models\VisitList; ?>
<h2 style="margin:0;">
	<?php echo $this->html->link($user->getName(), "/profile/show/" . $user->getId(), array("rel"=>"nofollow")); ?>
</h2>
<?php if ($user->getPhotoUrl()) { ?>
	<img src="<?php echo $user->getPhotoUrl(); ?>" />
<?php } ?>
<p>
	<?php echo $user->getUserInfo(); ?>
</p>

<?php

$uniqueVisits = array();
foreach ($visits->getItems() as $item) {
	if (isset($uniqueVisits[$item->getPlace()->getId()])) {
		$uniqueVisits[$item->getPlace()->getId()]++;
	} else {
		$uniqueVisits[$item->getPlace()->getId()] = 1;
	}
}
$diffCheckins = count($uniqueVisits);

$badge = 0;
$uniqueVisit = 0;
$badges = array(1, 5, 10, 25, 50, 100, 250, 500, 1000);
foreach ($badges as $badgeCount) {
	if ($badgeCount <= count($visits->getItems())) {
		$badge = $badgeCount;
	}
	
	if ($badgeCount <= $diffCheckins) {
		$uniqueVisit = $badgeCount;
	}
}
if ($badge != 0) { ?>
<img title="<?php echo $badge ?> checkins" src="<?php echo ROOT_URL; ?>img/badges/<?php echo $badge; ?>_orange.jpg" />
<?php } 
if ($uniqueVisit != 0) { ?>
<img title="checkins em <?php echo $uniqueVisit; ?> locais diferentes" src="<?php echo ROOT_URL; ?>img/badges/<?php echo $uniqueVisit; ?>_blue.jpg" />
<?php } ?>

<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider"><?php echo $title; ?></li>
	<?php if ($visits instanceof VisitList && $visits->getItems()) { ?>
		<?php foreach ($visits->getItems() as $visit) { ?>
			<li tabindex="0" class="ui-li ui-btn ui-btn-up-c" data-theme="c">
				<h3 class="ui-li-heading">
					<?php echo $this->html->link($visit->getPlace()->getName(), "/places/show/" . $visit->getPlace()->getId() . ""); ?>
				</h3>
				<p class="ui-li-desc">
					<?php echo $visit->getDate(); ?>
				</p>
			</li>
		<?php } ?>
	<?php } else { ?>
		<li>Nenhum check-in encontrado.</li>
	<?php } ?>
</ul>
