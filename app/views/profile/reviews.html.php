<?php use app\models\ReviewList; ?>
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
	<?php if ($user->getReviews() instanceof ReviewList && $user->getReviews()->getNumFound() > 0) { ?>
		<?php foreach ($user->getReviews()->getItems() as $review) { ?>
			<li tabindex="0" class="ui-li ui-btn ui-btn-up-c" data-theme="c">
				<h3 class="ui-li-heading"><?php echo $this->html->link($review->getPlace()->getName(), $review->getPlace()->getShortPlaceUrl() . ""); ?></h3>
				<p class="ui-li-desc">
					<?php echo $review->getContent(); ?>
				</p>
			</li>
		<?php } ?>
		<?php if ($user->getReviews()->getNumFound() >= $user->getReviews()->getCurrentPage() * 10) { ?>
			<li><a href="<?php echo ROOT_URL;?>profile/reviews/<?php echo $user->getId(); ?>/page<?php echo $user->getReviews()->getCurrentPage() + 1; ?>">Mais</a></li>
		<?php } ?>
	<?php } else { ?>
	<li>Nenhuma avaliação.</li>
	<?php } ?>
</ul>
