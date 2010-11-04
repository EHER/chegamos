<?php if ($search->search->result_count): ?>
	<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
		<li data-role="list-divider">Locais da categoria: <?= str_replace('_',' ',$categoryName); ?></li>
		<?php foreach ($search->search->places as $place): ?>
			<li>
				<span class="placename">
					<?php echo $this->html->link(lithium\util\Inflector::formatTitle($place->place->name), "/places/show/" . $place->place->id . ""); ?>
				</span>
				<br />
				<span class="address">
					<?php echo lithium\util\Inflector::formatTitle($place->place->address->street) . ", " . $place->place->address->number; ?>
				</span>
			</li>
		<?php endforeach; ?>
	</ul>
<?php else: ?>
    <p>Nenhum local encontrado.</p>
<?php endif; ?>
