<?php use \app\models\User; ?>
<?php use app\models\VisitList; ?>
<h2 style="margin:0;">
	<?php echo $this->html->link($user->getName(), $user->getProfileUrl(), array("rel"=>"nofollow")); ?>
</h2>
<?php if ($user->getPhotoUrl()) { ?>
	<img src="<?php echo $user->getPhotoUrl(); ?>" />
<?php } ?>
<p>
	<?php echo $user->getUserInfo(); ?>
</p>

<ul data-role="listview" data-inset="true" data-theme="<?php echo THEME_LIST; ?>" data-dividertheme="<?php echo THEME_MAIN; ?>">
	<li data-role="list-divider"><?php echo $title; ?></li>
	<?php if ($visits instanceof VisitList && $visits->getItems()) { ?>
		<?php foreach ($visits->getItems() as $visit) { ?>
			<li class="ui-li ui-btn ui-btn-up-c" data-theme="<?php echo THEME_LIST; ?>">
				<a href="<?php echo $visit->getPlace()->getShortPlaceUrl();?>">
					<h3><?php echo $visit->getPlace()->getName(); ?></h3>
					<p class="ui-li-desc">
						<?php echo $visit->getDate(); ?>
					</p>
				</a>
			</li>
		<?php } ?>
	<?php } else { ?>
		<li>Nenhum check-in encontrado.</li>
	<?php } ?>
</ul>
