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
<h3>Locais por categoria</h3>
<ul>
	<li><?php echo $this->html->link("Voltar", "/"); ?></li>
</ul>
<?php if ($categories): ?>
    <ul>
        <?php foreach ($categories->categories as $category): ?>
            <li><?php echo $this->html->link($category->category->name, "/places/category/" . $category->category->id); ?></li>
        <?php endforeach; ?>
        <li><?php echo $this->html->link("Todas as categorias", "/places/categories?all"); ?></li>
    </ul>
<?php else: ?>
    <p>Nenhum local próximo.</p>
<?php endif; ?>