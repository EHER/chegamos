<?php use \app\models\User; ?>
<?php use app\models\PlayerProfile; ?>
<h2 style="margin:0;">
	<?php echo $this->html->link($user->getName(), $user->getProfileUrl(), array("rel"=>"nofollow")); ?>
</h2>
<?php if ($user->getPhotoUrl()) { ?>
	<img src="<?php echo $user->getPhotoUrl(); ?>" />
<?php } ?>
<p>
    <img src="<?php echo $playerProfile->getLevel()->getImage(); ?>" width="20px" height="20px" alt="<?php echo $playerProfile->getLevel()->getName(); ?>">
    <strong><?php echo $playerProfile->getLevel()->getName(); ?></strong>
    com <?php echo $playerProfile->getPointsAll(); ?> Pts
</p>

<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider"><?php echo $title; ?></li>
	<?php if ($playerProfile instanceof PlayerProfile && $playerProfile->getBadges()) { ?>
		<?php foreach ($playerProfile->getBadges() as $badge) { ?>
			<li class="ui-li ui-btn ui-btn-up-c" data-theme="c">
                <h3><?php echo $badge->getName(); ?></h3>
                <p class="ui-li-desc">
                    <?php echo $badge->getMessage(); ?>
                </p>
			</li>
		<?php } ?>
	<?php } else { ?>
		<li>Nenhuma conquista encontrada.</li>
	<?php } ?>
</ul>
