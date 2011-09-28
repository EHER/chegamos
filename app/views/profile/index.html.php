<ul data-role="listview" data-inset="true" data-theme="<?php echo THEME_LIST; ?>" data-dividertheme="<?php echo THEME_MAIN; ?>">
	<li data-role="list-divider">Locais</li>
	<li><?php echo $this->html->link('Locais por Nome', "/places/search"); ?></li>
	<li><?php echo $this->html->link('Locais por Categoria', "/places/categories"); ?></li>
	<li><?php echo $this->html->link('Locais Próximos', "/places/near"); ?></li>
</ul>
