<?php if ($categories): ?>
	<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
		<li data-role="list-divider">Categorias</li>
		<?php if (isset($_GET['all'])): ?>
			<li><?php echo $this->html->link("Principais categorias", "/places/categories"); ?></li>
		<?php else: ?>
			<li><?php echo $this->html->link("Todas as categorias", "/places/categories?all"); ?></li>
		<?php endif; ?>
		<li><input placeholder="Filter results..." data-type="search" class="ui-input-text ui-body-c"></li>
		<?php foreach ($categories->categories as $category): ?>
            <li><?php echo $this->html->link(lithium\util\Inflector::formatTitle($category->category->name), "/places/category/0" . $category->category->id); ?></li>
        <?php endforeach; ?>
	</ul>
<?php else: ?>
    <p>Nenhuma categoria encontrada.</p>
<?php endif; ?>
