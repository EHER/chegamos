<?php use app\models\PlaceList; ?>

<ul data-role="listview" data-inset="true" data-theme="<?php echo THEME_LIST; ?>" data-dividertheme="<?php echo THEME_MAIN; ?>">
	<li data-role="list-divider">
		Informação de Combustível - Raio: <?php echo number_format($placeList->getRadius(),0,",","."); ?> metros
	</li>
	<?php if ($placeList instanceof PlaceList && $placeList->getNumFound() > 0) { ?>
		<?php foreach ($placeList->getItems() as $place) { ?>
		    <li>
				<span class="placename">
					<?php echo $this->html->link($place->getName(), $place->getPlaceUrl() . ""); ?>
				</span>
				<br />
				<p class="ui-li-desc">
					<?php echo $place->getAddress(); ?>
				</p>
			</li>
		<?php } ?>
	<?php } else { ?>
	<li>Nenhum local encontrado.</li>
	<?php } ?>
</ul>
