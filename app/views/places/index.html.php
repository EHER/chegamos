<form action="" method="GET">
<fieldset>
	<legend>Busca de local</legend>
	CEP: <input type="text" name="cep" value="<?=$_GET['cep'];?>">
</fieldset>
<input type="submit" value="buscar">
</form>

<?php if($search):?>
	<h3>Resultado da busca:</h3>
	<ul>
	<?php foreach($search['search']['places'] as $place):?>
		<li>
			<a href="/places/show/<?=$place['place']['id'];?>"><?=$place['place']['name'];?></a>
		</li>
	<?php endforeach;?>
	</ul>
<?php endif;?>