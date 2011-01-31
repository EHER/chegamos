<?php use app\models\ReviewList; ?>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider"><?php echo $title; ?></li>
	<?php if ($reviews instanceof ReviewList && $reviews->getNumFound() > 0) { ?>
		<?php foreach ($reviews->getItems() as $review) { ?>
		    <li>
				<span class="placename">
					<img width="64" height="64" src="<?php echo ($reviews->getPhotoMediumUrl() ? $reviews->getPhotoMediumUrl() : 'http://www.apontador.com.br/apontador_v8/images/accounts/user64.gif'); ?>" class="ui-li-thumb">
					<?php echo $this->html->link($review->getPlace()->getName(), "/places/show/" . $review->getPlace()->getId() . ""); ?>
				</span>
				<br />
				<p class="ui-li-desc">
					<p>
						<?php echo $review->getContent(); ?>
					</p>
				</p>
			</li>
		<?php } ?>
		<?php if ($reviews->getNumFound() >= $reviews->getCurrentPage() * 10) { ?>
			<li><a href="<?php echo ROOT_URL;?>profile/reviews/<?php echo $reviews->getId(); ?>/page<?php echo $reviews->getCurrentPage() + 1; ?>">Mais</a></li>
		<?php } ?>
	<?php } else { ?>
	<li>Nenhuma avaliação.</li>
	<?php } ?>
</ul>
