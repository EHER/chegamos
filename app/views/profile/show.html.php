<?php use \app\models\User; ?>
<?php use \app\models\UserStats; ?>

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
	<li>
		<?php echo $this->html->link("Avaliações (" . $user->getStats()->getReviews() . ")", "/profile/reviews/" . $user->getId(), array("rel" => "nofollow")); ?>
	</li>
	<li>
		<?php echo $this->html->link("Locais Cadastrados (" . $user->getStats()->getPlaces() . ")", "/profile/places/" . $user->getId(), array("rel" => "nofollow")); ?>
	</li>
	<li>
		<?php echo $this->html->link("Fotos (" . $user->getStats()->getPhotos() . ")", "/profile/photos" .$user->getId(), array("rel" => "nofollow")); ?>
	</li>
</ul>
