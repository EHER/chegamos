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

<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider"><?php echo $title; ?></li>
	<?php if ($visits instanceof VisitList && $visits->getItems()) { ?>
		<?php foreach ($visits->getItems() as $visit) { ?>
		    <li>
				<span class="placename">
					<?php echo $this->html->link($visit->getPlace()->getName(), "/places/show/" . $visit->getPlace()->getId() . ""); ?>
				</span>
				<br />
				<p class="ui-li-desc">
					<?php echo $visit->getDate(); ?>
				</p>
			</li>
		<?php } ?>
	<?php } else { ?>
	<li>Nenhum check-in encontrado.</li>
	<?php } ?>
</ul>
