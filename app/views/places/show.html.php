<h2 style="margin:0;">
	<?php echo $place->getName(); ?>
	<?php if($place->getAverageRatingString()) { ?>
		<small>(<?php echo $place->getAverageRatingString() ?>)</small>
	<?php } ?>
</h2>
<p><?php echo $place->getAddress(); ?></p>
<p>
	<?php if(!empty($place->place->phone->number)) echo 'Fone:'; ?>
	<?php if(!empty($place->place->phone->country)) echo '+' . $place->place->phone->country; ?>
	<?php if(!empty($place->place->phone->area)) echo '(' . $place->place->phone->area . ')'; ?>
	<?php if(!empty($place->place->phone->number)) echo $place->place->phone->number; ?>
</p>
<p>Categoria: <?php echo $place->getCategory(); ?></p>
<p><?php echo $place->getDescription(); ?></p>

<p>
	Cadastrado por:
	<a href="http://www.apontador.com.br/profile/<?php echo $place->getCreated()->user->id; ?>.html" target="_blank">
		<?php echo $place->getCreated()->user->name; ?>
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
		<?php echo $this->html->link("Estou aqui", "/places/checkin?placeId=" . $place->getId()); ?>
	</li>
	<li>
		<?php echo $this->html->link("Quem esteve aqui", "/places/checkins/" . $place->getId()); ?>
	</li>
	<li>
		<?php echo $this->html->link("Avaliações"/* (" . $place->place->review_count . ")"*/, "/places/review/" . $place->getId()); ?>
	</li>
	<li>
		<?php
		echo $this->html->link(
				"Ver no Apontador",
				$place->getMainUrl(),
				array("target" => "_blank")
		); ?>
	</li>
	<li>
		<?php
		echo $this->html->link(
				"Como chegar",
				"http://maplink.apontador.com.br/?placeid=@" . $place->getId(),
				array("target" => "_blank")
		); ?>
	</li>
</ul>
