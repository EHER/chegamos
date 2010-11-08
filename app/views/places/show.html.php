<?php use \app\models\GasStation; ?>
<?php use \app\models\PlaceInfo; ?>

<h2 style="margin:0;">
	<?php echo $place->getName(); ?>
	<?php if($place->getAverageRatingString()) { ?>
		<small>(<?php echo $place->getAverageRatingString() ?>)</small>
	<?php } ?>
</h2>
<p><?php echo $place->getAddress(); ?></p>
<p>
	<?php if(!empty($place->getPhone()->number)) echo 'Fone:'; ?>
	<?php if(!empty($place->getPhone()->country)) echo '+' . $place->getPhone()->country; ?>
	<?php if(!empty($place->getPhone()->area)) echo '(' . $place->getPhone()->area . ')'; ?>
	<?php if(!empty($place->getPhone()->number)) echo $place->getPhone()->number; ?>
</p>
<p>Categoria: <?php echo $place->getCategory(); ?></p>
<p><?php echo $place->getDescription(); ?></p>

<p>
	Cadastrado por:
	<a href="http://www.apontador.com.br/profile/<?php echo $place->getCreated()->user->id; ?>.html" target="_blank">
		<?php echo $place->getCreated()->user->name; ?>
	</a>
</p>

<?php if($place->getPlaceInfo() instanceof PlaceInfo && $place->getPlaceInfo()->getGasStation() instanceof GasStation) { ?>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">
		Preços de combustível
	</li>
	<?php foreach ($place->getPlaceInfo()->getGasStation()->getItems() as $item) { ?>
		<?php if ($item->getValue()) { ?>
			<li>
				<?php echo $item; ?>
			</li>
		<?php } ?>
	<?php } ?>
</ul>
<?php } ?>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li>
		<?php echo $this->html->link("Estou aqui", "/places/checkin?placeId=" . $place->getId(), array('rel'=>'external')); ?>
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
