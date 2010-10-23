<?php if ($search and !isset ($_GET['checkin'])): ?>
	<h3>
		Locais próximos ao CEP <?=$zipcode; ?>
		<?php
			echo $this->html->link("(mudar onde estou)","/?checkin");
		?>
	</h3>

	<ul>
	<?php foreach ($search->search->places as $place): ?>
	<li>
		<?php echo $this->html->link($place->place->name, "/places/show/" . $place->place->id . ""); ?>
	</li>
	<?php endforeach; ?>
	</ul>
<?php else: ?>
	<h3>Onde você está?</h3>
	<span>
	<form action="" method="GET" style="width: 115px;">
		<label for="cep">CEP:</label>
		<input type="text" id="cep" name="cep" value="<?=$zipcode; ?>" style="width: 100px;">
		<input type="submit" value="Estou aqui">
		</form>
	</span>

<?php endif; ?>