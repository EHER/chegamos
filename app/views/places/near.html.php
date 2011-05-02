<?php use app\models\PlaceList; ?>

<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Locais Próximos</li>
	<?php if ($placeList instanceof PlaceList && $placeList->getNumFound() > 0) { ?>
		<?php foreach ($placeList->getItems() as $place) { ?>
			<li>
				<a href="<?php echo $place->getPlaceUrl(); ?>">
					<h3>
						<?php echo $place->getName(); ?>
					</h3>
					<p>
						<?php echo $place->getAddress(); ?>
					</p>
				</a>
			</li>
		<?php } ?>
		<?php if ($page < 10) { ?>
			<li><a href="<?php echo ROOT_URL;?>places/near/page<?php echo $page + 1; ?>">Mais</a></li>
		<?php } ?>
	<?php } else { ?>
		<li>Nenhum local encontrado.</li>
	<?php } ?>
</ul>
