<?php use app\models\PlaceList; ?>

<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider"><?php echo $categoryName; ?></li>
	<?php if ($placeList instanceof PlaceList && $placeList->getNumFound() > 0) { ?>
		<?php foreach ($placeList->getItems() as $place) { ?>
			<li>
				<span class="placename">
					<?php echo $this->html->link($place->getName(), $place->getPlaceUrl()); ?>
				</span>
				<br />
				<p class="ui-li-desc">
					<?php echo $place->getAddress()->getStreet() . ", " . $place->getAddress()->getNumber(); ?>
				</p>
			</li>
		<?php } ?>
		<?php if ($page < 10) { ?>
			<li><a href="<?php echo ROOT_URL;?>places/category/<?php echo $categoryId; ?>/page<?php echo $page + 1; ?>">Mais</a></li>
		<?php } ?>
	<?php } else { ?>
	<li>Nenhum local encontrado.</li>
	<?php } ?>
</ul>
