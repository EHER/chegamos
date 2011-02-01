<?php use app\models\VisitorList; ?>
<h2 style="margin:0;">
	<?php echo $this->html->link($place->getName(), "/places/show/" . $place->getId()); ?>
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

<ul data-role="listview" role="listbox" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">
		Quem esteve aqui
		<span class="ui-li-count ui-btn-up-c ui-btn-corner-all"><?php echo $visitors instanceof VisitorList ? $visitors->getNumFound() : '' ?></span>
	</li>
<?php if ($visitors instanceof VisitorList && $visitors->getNumFound() > 0) { ?>
	<?php foreach ($visitors->getItems() as $visitor) { ?>
		<li tabindex="0" class="ui-li-has-thumb ui-li ui-btn ui-btn-up-c" data-theme="c">
				<img width="64" height="64" src="<?php echo ($visitor->getPhotoMediumUrl() ? $visitor->getPhotoMediumUrl() : 'http://www.apontador.com.br/apontador_v8/images/accounts/user64.gif'); ?>">
				<h3 class="ui-li-heading"><a href="<?php echo $visitor->getProfileUrl(); ?>" class="ui-link-inherit"><?php echo $visitor->getName(); ?></a></h3>
				<p class="ui-li-desc">(<?php echo $visitor->getVisits() . ($visitor->getVisits() == 1 ? ' visita' : ' visitas'); ?>)
				em <?php echo $visitor->getLastVisit(); ?></p>
		</li>
	<?php } ?>
<?php } else { ?>
	<li>Ninguém registrou a presença neste local.</li>
<?php } ?>
</ul>
