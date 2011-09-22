<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Locais</li>
	<li><?php echo $this->html->link('Locais por Categoria', "/places/categories"); ?></li>
	<li><?php echo $this->html->link('Locais por perto', "/places/near"); ?></li>
	<li><?php echo $this->html->link('Locais Visitados', "/profile/visits", array("rel"=>"external")); ?></li>
</ul>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Pessoas</li>
	<li><?php echo $this->html->link('Meu Perfil', "/profile/show", array("rel"=>"external")); ?></li>
	<li><?php echo $this->html->link('Pessoas por perto', "/profile/near"); ?></li>
</ul>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Informações</li>
	<li><?php echo $this->html->link('Ofertas por perto', "/deals/near"); ?></li>
<?php /* WIP
	<li><?php echo $this->html->link('Sobre o Chegamos!', "/about"); ?></li>
 */?>
</ul>
<?php /* WIP
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Todo</li>
	<li><?php echo $this->html->link('Fazer Avaliações', "/todo/reviews"); ?></li>
	<li><?php echo $this->html->link('Enviar Fotos', "/todo/photos"); ?></li>
</ul>
 */?>