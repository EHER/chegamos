<?php use \app\models\User; ?>
<?php use app\models\UserList; ?>
<ul data-role="listview" data-inset="true">
	<li data-role="list-divider"><?php echo $title;?></li>
	<?php if ($users instanceof UserList && $users->getNumFound() > 0) { ?>
		<?php foreach ($users->getItems() as $user) { ?>
			<li>
				<a href="<?php echo ROOT_URL."profile/show/" . $user->getId();?>">
					<img width="64" height="64" src="<?php echo ($user->getPhotoMediumUrl() ? $user->getPhotoMediumUrl() : 'http://www.apontador.com.br/apontador_v8/images/accounts/user64.gif'); ?>">
					<h3><?php echo $user->getName();?></h3>
					<p><?php echo $user->getLastVisitInfo(); ?></p>
				</a>
			</li>
		<?php } ?>
		<?php if ($users->getNumFound() >= $users->getCurrentPage() * 10) { ?>
			<li><a href="<?php echo ROOT_URL;?>profile/near/page<?php echo $users->getCurrentPage() + 1; ?>">Mais</a></li>
		<?php } ?>
	<?php } else { ?>
		<li tabindex="0" class="ui-li-has-thumb ui-li ui-btn ui-btn-up-c" data-theme="c">Ninguém.</li>
	<?php } ?>
</ul>
