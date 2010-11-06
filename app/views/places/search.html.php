<?php use app\models\PlaceList; ?>

<?php if ($searchName) { ?>

	<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
		<li data-role="list-divider">Locais Próximos</li>
		<?php if ($placeList instanceof PlaceList && $placeList->getNumFound() > 0) { ?>
			<?php foreach ($placeList->getItems() as $place) { ?>
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
			<label for="cep">Nome:</label>
			<input type="text" id="name" name="name" value="<?= $searchName; ?>">
		</fieldset>
		<input type="submit" value="Buscar">
	</form>
<?php } ?>
