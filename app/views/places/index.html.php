<form action="" method="GET">
    <fieldset>
        <legend>Busca de local</legend>
	CEP: <input type="text" name="cep" value="<?=$zipcode; ?>">
    </fieldset>
    <input type="submit" value="buscar">
</form>

<?php if ($search): ?>
    <h3>Resultado da busca:</h3>
    <ul>
    <?php foreach ($search->search->places as $place): ?>
        <li>
	    <?php echo $this->html->link($place->place->name, "/places/show/" . $place->place->id . ""); ?>
	    (<?php echo $this->html->link("estou aqui", "/places/checkin/" . $place->place->id . ""); ?>)
	</li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>