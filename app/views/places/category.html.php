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
<h3>Locais da categoria <?= str_replace('_',' ',$categoryName); ?></h3>
<?php if ($search): ?>
    <ul>
        <li><?php echo $this->html->link("Voltar", "/places/categories"); ?></li>
        <?php foreach ($search->search->places as $place): ?>
            <li><?php echo $this->html->link($place->place->name, "/places/show/" . $place->place->id . ""); ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nenhum local próximo.</p>
<?php endif; ?>