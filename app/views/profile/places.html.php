<?php use \app\models\User; ?>
<?php use app\models\PlaceList; ?>
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
	<?php if ($user->getPlaces() instanceof PlaceList && $user->getPlaces()->getNumFound() > 0) { ?>
		<?php foreach ($user->getPlaces()->getItems() as $place) { ?>
		    <li>
				<span class="placename">
					<?php echo $this->html->link($place->getName(), "/places/show/" . $place->getId() . ""); ?>
				</span>
				<br />
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
