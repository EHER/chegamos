<h2>
	<?= $place->place->name; ?>
        <?php if(!empty($place->place->average_rating)): ?>
	<small>(<?= $place->place->average_rating; ?>)</small>
        <?php endif; ?>
</h2>
<p>
	<?= $place->place->address->street; ?>,
	<?= $place->place->address->number; ?>
	<?= $place->place->address->complement; ?>
	<?= $place->place->address->district; ?>
	<br/>
	<?= $place->place->address->city->name; ?> -
	<?= $place->place->address->city->state; ?>
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

<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li>
		<?php echo $this->html->link("Estou aqui", "/places/checkin?placeId=" . $place->place->id); ?>
	</li>
	<li>
		<?php echo $this->html->link("Quem esteve aqui", "/places/checkins/" . $place->place->id); ?>
	</li>
	<li>
		<?php echo $this->html->link("Avaliações"/* (" . $place->place->review_count . ")"*/, "/places/review/" . $place->place->id); ?>
	</li>
	<li>
		<?php
		echo $this->html->link(
				"Ver no Apontador",
				"http://www.apontador.com.br/local/poi/" . $place->place->id . ".html",
				array("target" => "_blank")
		); ?>
	</li>
	<li>
		<?php
		echo $this->html->link(
				"Como chegar",
				"http://maplink.apontador.com.br/?placeid=@" . $place->place->id,
				array("target" => "_blank")
		); ?>
	</li>
</ul>
