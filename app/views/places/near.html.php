<?php use app\models\PlaceList; ?>

<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Locais Próximos</li>
	<?php if ($placeList instanceof PlaceList && $placeList->getNumFound() > 0) { ?>
		<?php foreach ($placeList->getItems() as $place) { ?>
			<li tabindex="0" class="ui-li ui-btn ui-btn-up-c" data-theme="c">
				<h3 class="ui-li-heading">
					<?php echo $this->html->link($place->getName(), $place->getPlaceUrl() . ""); ?>
				</h3>
				<p class="ui-li-desc">
					<?php echo $place->getAddress()->getStreet() . ", " . $place->getAddress()->getNumber(); ?>
				</p>
			</li>
		<?php } ?>
		<?php if ($page < 10) { ?>
			<li><a href="<?php echo ROOT_URL;?>places/near/page<?php echo $page + 1; ?>">Mais</a></li>
		<?php } ?>
	<?php } else { ?>
		<li>Nenhum local encontrado.</li>
	<?php } ?>
</ul>
