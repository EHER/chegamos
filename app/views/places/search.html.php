<?php use app\models\PlaceList; ?>

<?php if ($searchName) { ?>

	<?php if (strlen($suggestions)>0) { ?>
		<p>Voc&ecirc; quis dizer: <?php echo $suggestions;?></p>
	<?php } ?>
	<ul data-role="listview" data-inset="true">
		<li data-role="list-divider">Locais por nome</li>
		<?php if ($placeList instanceof PlaceList && $placeList->getNumFound() > 0) { ?>
			<?php foreach ($placeList->getItems() as $place) { ?>
				<li>
					<a href="<?php echo $place->getPlaceUrl(); ?>">
						<h3>
							<?php echo $place->getName(); ?>
						</h3>
						<p class="ui-li-desc">
							<?php echo $place->getAddress(); ?>
						</p>
					</a>
				</li>
			<?php } ?>
		<?php } else { ?>
			<li>Nenhum local encontrado.</li>
		<?php } ?>
	</ul>
<?php } else { ?>
	<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
		<li data-role="list-divider">Buscar local por nome</li>
	</ul>
	<form method="GET" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<fieldset>
			<label for="name">Nome:</label>
			<input type="text" id="name" name="name" value="<?php echo $searchName; ?>">
		</fieldset>
		<input type="submit" value="Buscar">
	</form>
<?php } ?>
