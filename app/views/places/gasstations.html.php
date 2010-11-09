<?php use app\models\PlaceList; ?>

<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">
		Informação de Combustível - Raio: <?php echo number_format($placeList->getRadius(),0,",","."); ?> metros
	</li>
	<?php if ($placeList instanceof PlaceList && $placeList->getNumFound() > 0) { ?>
		<?php foreach ($placeList->getItems() as $place) { ?>
		    <li>
				<span class="placename">
					<?php echo $this->html->link($place->getName(), "/places/show/" . $place->getId() . ""); ?>
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
