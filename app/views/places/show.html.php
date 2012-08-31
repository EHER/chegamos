<?php use \app\models\GasStation; ?>
<?php use \app\models\PlaceInfo; ?>
<div class="hreview-aggregate">
	<div class="item vcard">
		<div style="float: left; margin-right: 10px;">
			<h2 style="margin:0;">
				<?php echo $this->html->link($place->getName(), $place->getPlaceUrl(), array('rel'=>'nofollow', 'class'=>'fn org url')); ?>
				<?php if($place->getAverageRatingString()) { ?>
				<small>(<?php echo $place->getAverageRatingString() ?>)</small>
				<?php } ?>
			</h2>
			<div class="adr">
				<p>
					<span class="street-address"><?php echo $place->getAddress()->getStreet(); ?>, <?php echo $place->getAddress()->getNumber(); ?></span>
					<span class="locality"><?php echo $place->getAddress()->getCity()->getName(); ?></span>
					- <span class="region"><?php echo $place->getAddress()->getCity()->getState(); ?></span>
				</p>
				<p class="tel">
					<?php if(!empty($place->getPhone()->number)) echo 'Fone:'; ?>
					<?php if(!empty($place->getPhone()->country)) echo '+' . $place->getPhone()->country; ?>
					<?php if(!empty($place->getPhone()->area)) echo '(' . $place->getPhone()->area . ')'; ?>
					<?php if(!empty($place->getPhone()->number)) echo $place->getPhone()->number; ?>
				</p>
			</div>
			<p>Categoria: <span class="category"><?php echo $place->getCategory(); ?></span></p>
		</div>
		<img src="<?php echo $place->getMapUrl() ?>" width="150" height="150" class="logo photo"/>

		<p class="summary"><?php echo $place->getDescription(); ?></p>

		<p>
			Cadastrado por:
			<a href="<?php echo ROOT_URL . 'profile/show/' . $place->getCreated()->user->id; ?>">
				<?php echo $place->getCreated()->user->name; ?>
			</a>
		</p>

		<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $place->getPlaceUrl();?>" scrolling="no" frameborder="0" style="height: 62px; width: 100%" allowTransparency="true"></iframe>
	</div>
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
			<?php echo $this->html->link("Estou aqui (check-in)", "/places/checkin/" . $place->getId(), array('rel' => 'external')); ?>
		</li>
		<li>
			<?php echo $this->html->link("Quem esteve aqui", "/places/checkins/" . $place->getId()); ?>
			<span class="ui-li-count ui-btn-up-c ui-btn-corner-all"><?php echo $place->getNumVisitors(); ?></span>
		</li>
		<li>
			<?php echo $this->html->link("Avaliações", "/places/review/" . $place->getId(), array('rel' => 'external')); ?>
			<span class="ui-li-count ui-btn-up-c ui-btn-corner-all count"><?php echo $place->getReviewCount(); ?></span>
			<span class="rating" style="display:none"><?php echo $place->getAverageRating(); ?></span>
		</li>
		<li>
			<?php echo $this->html->link("Fotos", "/places/photos/" . $place->getId()); ?>
			<span class="ui-li-count ui-btn-up-c ui-btn-corner-all"><?php echo $place->getNumPhotos(); ?></span>
		</li>
		<li>
			<?php echo $this->html->link("Enviar fotos", "http://apontador.ricardomartins.info/upload_multiplo/?lbsid=" . $place->getId(), array('rel' => 'external nofollow')); ?>
		</li>
		<li>
<?php
echo $this->html->link(
	"Como chegar",
	$place->getRouteUrl($location),
	array("target" => "_blank", "rel" => "external")
);
?>
		</li>
		<li>
			<?php echo $this->html->link("Sou o dono", "/places/buy/" . $place->getId(), array('rel' => 'external')); ?>
		</li>
		<li>
<?php
echo $this->html->link(
	"Ver no Apontador",
	$place->getMainUrl(),
	array("target" => "_blank", "rel" => "external")
); ?>
		</li>
	</ul>
</div>
