<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Locais</li>
	<li><?php echo $this->html->link('Locais por Categoria', "/places/categories"); ?></li>
	<li><?php echo $this->html->link('Locais Próximos', "/places/near"); ?></li>
</ul>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Ofertas</li>
	<li><?php echo $this->html->link('Ofertas Próximas', "/deals/near"); ?></li>
</ul>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Pessoas</li>
	<li><?php echo $this->html->link('Quem eu sigo', "/profile/following"); ?></li>
	<li><?php echo $this->html->link('Quem me segue', "/profile/followers"); ?></li>
	<!-- WIP
	<li><?php echo $this->html->link('Pessoas por perto', "/profile/near"); ?></li>
	-->
</ul>
<!-- WIP
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Todo</li>
	<li><?php echo $this->html->link('Fazer Avaliações', "/todo/reviews"); ?></li>
	<li><?php echo $this->html->link('Enviar Fotos', "/todo/photos"); ?></li>
</ul>
-->

