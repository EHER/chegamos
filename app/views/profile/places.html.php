<?php use \app\models\User; ?>
<?php use app\models\PlaceList; ?>
<h2 style="margin:0;">
	<?php echo $this->html->link($user->getName(), $user->getProfileUrl(), array("rel"=>"nofollow")); ?>
</h2>
<?php if ($user->getPhotoUrl()) { ?>
	<a href="<?php echo $user->getProfileUrl();?>">
		<img src="<?php echo $user->getPhotoUrl(); ?>" alt="Foto de <?php echo $user->getName(); ?>"/>
	</a>
<?php } ?>
<p>
	<?php echo $user->getUserInfo(); ?>
</p>

<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider"><?php echo $title; ?></li>
	<?php if ($user->getPlaces() instanceof PlaceList && $user->getPlaces()->getNumFound() > 0) { ?>
		<?php foreach ($user->getPlaces()->getItems() as $place) { ?>
			<li tabindex="0" class="ui-li ui-btn ui-btn-up-c" data-theme="c">
				<h3 class="ui-li-heading">
					<?php echo $this->html->link($place->getName(), $place->getShortPlaceUrl()); ?>
				</h3>
				<p class="ui-li-desc">
					<?php echo $place->getAddress()->getStreet() . ", " . $place->getAddress()->getNumber(); ?>
				</p>
			</li>
		<?php } ?>
		<?php if ($user->getPlaces()->getNumFound() >= $user->getPlaces()->getCurrentPage() * 10 ) { ?>
			<li><a href="<?php echo ROOT_URL;?>profile/places/<?php echo $user->getId(); ?>/page<?php echo $user->getPlaces()->getCurrentPage() + 1; ?>">Mais</a></li>
		<?php } ?>
	<?php } else { ?>
	<li>Nenhum local.</li>
	<?php } ?>
</ul>
