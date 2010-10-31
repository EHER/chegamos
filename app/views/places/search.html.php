<h3>
    Onde estou
    <?php echo $this->html->link("(mudar)", "/places/checkin"); ?>
</h3>

<p>
<?php if ($placeName): ?>
<a href="/places/show/<?= $placeId; ?>"><?= $placeName; ?></a>
<?php endif; ?>

<?php if ($zipcode): ?>
CEP: <?= $zipcode; ?>
<?php endif; ?>

<?php if ($cityState): ?>
<?= $cityState; ?>
<?php endif; ?>

<?php if ($lat and $lng): ?>
(<?= $lat; ?>, <?= $lng; ?>)
<?php endif; ?>
</p>

<?php if ($searchName): ?>
	<h3>
		Locais com nome "<?= $searchName; ?>"
		<?php echo $this->html->link("(mudar)", "/places/search"); ?>
	</h3>
	<ul>
		<li><?php echo $this->html->link("Voltar", "/"); ?></li>
	</ul>
	<?php if ($search and $search->search->result_count): ?>
		<ul>
			<?php foreach ($search->search->places as $place): ?>
				<li>
					<span class="placename">
						<?php echo $this->html->link(lithium\util\Inflector::formatTitle($place->place->name), "/places/show/" . $place->place->id . ""); ?>
					</span>
					<br />
					<span class="address">
						<?php echo lithium\util\Inflector::formatTitle($place->place->address->street) . ", " . $place->place->address->number; ?>
					</span>
					<br />
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p>Nenhum local encontrado.</p>
	<?php endif; ?>
<?php else: ?>
	<h3>
		Buscar local por nome
	</h3>

	<form method="GET" style="width: 180px;">
		<fieldset>
			<label for="cep">Nome:</label>
			<input type="text" id="name" name="name" value="<?= $searchName; ?>" style="width: 100px;">
		</fieldset>
		<input type="submit" value="Buscar">
	</form>
<?php endif; ?>
