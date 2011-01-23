<?php use \app\models\Deal; ?>
<?php use app\models\DealsList; ?>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Ofertas por perto</li>
	<?php if ($following instanceof FollowingList && $following->getNumFound() > 0) { ?>
		<?php foreach ($following->getItems() as $user) { ?>
		    <li>
				<span class="placename">
					<img width="64" height="64" src="<?php echo ($user->getPhotoMediumUrl() ? $user->getPhotoMediumUrl() : 'http://www.apontador.com.br/apontador_v8/images/accounts/user64.gif'); ?>" class="ui-li-thumb">
					<?php echo $this->html->link($user->getName(), "/profile/show/" . $user->getId() . ""); ?>
				</span>
				<br />
				<p class="ui-li-desc">
					<p>
						<?php echo $user->getLastVisitInfo(); ?>
					</p>
				</p>
			</li>
		<?php } ?>
		<?php if ($following->getNumFound() >= $following->getCurrentPage() * 10) { ?>
			<li><a href="<?php echo ROOT_URL;?>profile/following/<?php echo $following->getUserId(); ?>/page<?php echo $following->getCurrentPage() + 1; ?>">Mais</a></li>
		<?php } ?>
	<?php } else { ?>
	<li>Você não segue ninguém.</li>
	<?php } ?>
</ul>
