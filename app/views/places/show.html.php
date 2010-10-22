<h2>
	<?php echo $place->place->name; ?>
	<small>(<?php echo $place->place->average_rating; ?> estrelas)</small>
</h2>
<p>
	<?php echo $place->place->address->street; ?>,
	<?php echo $place->place->address->number; ?>
	<?php echo $place->place->address->complement; ?>
	<?php echo $place->place->address->district; ?>
	<br/>
	<?php echo $place->place->address->city->name; ?> -
	<?php echo $place->place->address->city->state; ?>
</p>

<p>
	<?php if(!empty($place->place->phone->number)) echo 'Fone:'; ?>
	<?php if(!empty($place->place->phone->country)) echo '+' . $place->place->phone->country; ?>
	<?php if(!empty($place->place->phone->area)) echo '(' . $place->place->phone->area . ')'; ?>
	<?php if(!empty($place->place->phone->number)) echo $place->place->phone->number; ?>
</p>
<p><?php echo $place->place->description; ?></p>
<?php /*
<p><?php echo round($place->place->thumbs->up / $place->place->thumbs->total * 100); ?>% recomendam</p>
<p><?php echo $place->place->category->name; ?> > <?php echo $place->place->category->subcategory->name; ?></p>
<p><?php echo $place->place->click_count; ?> visitas</p>
<p><?php echo $place->place->review_count; ?> avaliações</p>
<p><?php echo $place->place->point->lat; ?></p>
<p><?php echo $place->place->point->lng; ?></p>
 */?>
<p>
	Cadastrado por:
	<a href="http://www.apontador.com.br/profile/<?php echo $place->place->created->user->id; ?>.html" target="_blank">
		<?php echo $place->place->created->user->name; ?>
	</a>
</p>

<ul>
	<li>
		<?php echo $this->html->link("Voltar", "/"); ?>
	</li>
	<li>
		<?php
		echo $this->html->link(
				"Veja no Apontador",
				"http://www.apontador.com.br/local/poi/" . $place->place->id . ".html",
				array("target" => "_blank")
		); ?>
	</li>
	<?php if(!empty($place->place->address->zipcode) and intval($place->place->address->zipcode)!=0): ?>
	<li>
		<?php echo $this->html->link("Estou aqui", "/places/?cep=" . $place->place->address->zipcode); ?>
	</li>
	<?php endif; ?>
</ul>

