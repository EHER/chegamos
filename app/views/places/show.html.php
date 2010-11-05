<h2 style="margin:0;">
	<?php echo $place->place->name; ?>
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
<p>Categoria: <?php echo $place->place->category->subcategory->name; ?></p>
<p><?php echo $place->place->description; ?></p>
<?php /*
<p><?php echo round($place->place->thumbs->up / $place->place->thumbs->total * 100); ?>% recomendam</p>
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

<?php 
	$gasStationData = array(
		'price_alcohol' => 'Álcool',
		'price_gasoline' => 'Gasolina',
		'price_gasoline_aditivada' => 'Gasolina Aditivada',
		'price_gasoline_podium' => 'Gasolina Pódium',
		'price_gasoline_premium' => 'Gasolina Premium',
		'price_gnv' => 'Gás Natural',
		'price_kerosene' => 'Querosene'
	);
?>

<?php if(isset($place->place->extended->gas_station)) { ?>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Preços de combustível</li>
	<?php foreach ($place->place->extended->gas_station as $name => $info) { ?>
		<?php if (strstr($name, 'price_') && $info != 0) { ?>
			<li>
				<?php echo $gasStationData[$name] . ': R$ ' . $info; ?>
			</li>
		<?php } ?>
	<?php } ?>
</ul>
<?php } ?>
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
