<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Locais</li>
	<li><?php echo $this->html->link('Locais por Categoria', "/places/categories"); ?></li>
	<li><?php echo $this->html->link('Locais Próximos', "/places/near"); ?></li>
	<li><?php echo $this->html->link('Últimas visitas', "/profile/visits", array("rel"=>"external")); ?></li>
</ul>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Pessoas</li>
	<li><?php echo $this->html->link('Amigos', "/profile/following", array("rel"=>"external")); ?></li>
	<li><?php echo $this->html->link('Seguidores', "/profile/followers", array("rel"=>"external")); ?></li>
	<!-- WIP
	<li><?php echo $this->html->link('Pessoas por perto', "/profile/near"); ?></li>
	-->
</ul>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Ofertas</li>
	<li><?php echo $this->html->link('Ofertas por perto', "/deals/near"); ?></li>
</ul>
<!-- WIP
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Todo</li>
	<li><?php echo $this->html->link('Fazer Avaliações', "/todo/reviews"); ?></li>
	<li><?php echo $this->html->link('Enviar Fotos', "/todo/photos"); ?></li>
</ul>
-->

