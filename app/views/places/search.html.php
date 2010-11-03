<?php if ($searchName): ?>
	<?php if ($search and $search->search->result_count): ?>
		<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
			<li data-role="list-divider">Locais com nome "<?= $searchName; ?>"</li>
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
	<h3>Buscar local por nome</h3>
	<form method="GET" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<fieldset>
			<label for="cep">Nome:</label>
			<input type="text" id="name" name="name" value="<?= $searchName; ?>">
		</fieldset>
		<input type="submit" value="Buscar">
	</form>
<?php endif; ?>