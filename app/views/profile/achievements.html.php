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

<ul data-role="listview" data-inset="true" data-theme="<?php echo THEME_LIST; ?>" data-dividertheme="<?php echo THEME_MAIN; ?>">
	<li data-role="list-divider"><?php echo $title; ?></li>
	<?php if ($playerProfile instanceof PlayerProfile && $playerProfile->getBadges()) { ?>
		<?php foreach ($playerProfile->getBadges() as $badge) { ?>
   		    <li class="ui-li-has-thumb ui-btn ui-btn-icon-right ui-li ui-btn-down-c ui-btn-up-c">
				<?php if($badge->getImage()) { ?>
					<img style="float:left; margin: 0 5px 5px 0" title="<?php echo $badge->getName(); ?>" alt="<?php echo $badge->getName(); ?>" width="84" height="84" src="<?php echo $badge->getImage(); ?>">
				<?php } ?>
				<p style="white-space: normal; margin-top:0px;" class="ui-li-desc" style="white-space:normal;">
                    <h3><?php echo $badge->getName(); ?></h3>
                    <?php echo $badge->getMessage(); ?>
				</p>
			</li>
		<?php } ?>
	<?php } else { ?>
		<li>Nenhuma conquista encontrada.</li>
	<?php } ?>
</ul>
