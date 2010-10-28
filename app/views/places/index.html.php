<h3>
    Onde estou
    <?php echo $this->html->link("(mudar)", "/places/checkin"); ?>
</h3>

<p>
<?php if ($placeName): ?>
<a href="/places/show/<?= $placeId; ?>"><?= $placeName; ?></a>
<?php endif; ?>

<?php if ($zipcode): ?>
CEP: <?= $zipcode; ?>
<?php endif; ?>

<?php if ($cityState): ?>
<?= $cityState; ?>
<?php endif; ?>

<?php if ($lat and $lng): ?>
(<?= $lat; ?>, <?= $lng; ?>)
<?php endif; ?>
</p>
<h3>Locais</h3>
<ul>
	<li><?php echo $this->html->link('Locais por Nome', "/places/search"); ?></li>
	<li><?php echo $this->html->link('Locais por Categoria', "/places/categories"); ?></li>
	<li><?php echo $this->html->link('Locais Próximos', "/places/near"); ?></li>
</ul>