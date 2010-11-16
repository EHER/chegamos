<?php use \app\models\CategoryList; ?>

<?php //<li><input placeholder="Filter results..." data-type="search" class="ui-input-text ui-body-c"></li> ?>

<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Categorias</li>
<?php if ($categories instanceof CategoryList && $categories->getNumFound() > 0) { ?>

		<?php if (!empty($all)) { ?>
			<li><?php echo $this->html->link("Principais categorias", "/places/categories"); ?></li>
		<?php } else { ?>
			<li><?php echo $this->html->link("Todas as categorias", "/places/categories/all"); ?></li>
		<?php } ?>
		
		<?php foreach ($categories->getItems() as $category) { ?>
            <li><?php echo $this->html->link($category->getName(), "/places/category/0" . $category->getId()); ?></li>
        <?php } ?>
	
<?php } else { ?>
    <li>Nenhuma categoria encontrada.</li>
<?php } ?>
</ul>
