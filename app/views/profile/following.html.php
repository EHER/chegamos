<?php use \app\models\User; ?>
<?php use app\models\FollowingList; ?>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider"><?php echo $title;?></li>
	<?php if ($following instanceof FollowingList && $following->getNumFound() > 0) { ?>
		<?php foreach ($following->getItems() as $user) { ?>
			<li tabindex="0" class="ui-li-has-thumb ui-li ui-btn ui-btn-up-c" data-theme="c">
				<a href="<?php echo ROOT_URL."profile/show/" . $user->getId();?>" class="ui-link-inherit">
					<img width="64" height="64" src="<?php echo ($user->getPhotoMediumUrl() ? $user->getPhotoMediumUrl() : 'http://www.apontador.com.br/apontador_v8/images/accounts/user64.gif'); ?>">
					<h3 class="ui-li-heading"><?php echo $user->getName();?></h3>
				</a>
				<p class="ui-li-desc">
					<?php echo $user->getLastVisitInfo(); ?>
				</p>
			</li>
		<?php } ?>
		<?php if ($following->getNumFound() >= $following->getCurrentPage() * 10) { ?>
			<li><a href="<?php echo ROOT_URL;?>profile/following/<?php echo $following->getUserId(); ?>/page<?php echo $following->getCurrentPage() + 1; ?>">Mais</a></li>
		<?php } ?>
	<?php } else { ?>
		<li tabindex="0" class="ui-li-has-thumb ui-li ui-btn ui-btn-up-c" data-theme="c">Ninguém.</li>
	<?php } ?>
</ul>
