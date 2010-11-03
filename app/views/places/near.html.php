<?php if ($search and $search->search->result_count): ?>
    <ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
		<li data-role="list-divider">Locais Próximos</li>
		<?php foreach ($search->search->places as $place): ?>
            <li>
				<span class="placename">
					<?php echo $this->html->link(lithium\util\Inflector::formatTitle($place->place->name), "/places/show/" . $place->place->id . ""); ?>
				</span>
				<br />
				<p class="ui-li-desc">
					<?php echo lithium\util\Inflector::formatTitle($place->place->address->street) . ", " . $place->place->address->number; ?>
				</p>
			</li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nenhum local encontrado.</p>
<?php endif; ?>
