<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Locais</li>
	<li><?php echo $this->html->link('Locais por Nome', "/places/search", array('rel' => 'external')); ?></li>
	<li><?php echo $this->html->link('Locais por Categoria', "/places/categories"); ?></li>
	<li><?php echo $this->html->link('Locais Próximos', "/places/near"); ?></li>
</ul>
<!--// Em desenvolvimento
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Pessoas</li>
	<li><?php echo $this->html->link('Quem eu sigo', "/places/search", array('rel' => 'external')); ?></li>
	<li><?php echo $this->html->link('Quem me segue', "/places/categories"); ?></li>
	<li><?php echo $this->html->link('Pessoas por perto', "/places/near"); ?></li>
</ul>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Todo</li>
	<li><?php echo $this->html->link('Enviar Fotos', "/places/search", array('rel' => 'external')); ?></li>
	<li><?php echo $this->html->link('Fazer Avaliações', "/places/categories"); ?></li>
</ul>
-->