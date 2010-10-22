<h2>Você está em <strong><?php echo $place->place->name; ?></strong>?</h2>
<p><?php echo $place->place->description; ?></p>
<form method="POST">
	<input type="submit" id="sim" name="sim" value="Sim" class="left"/>
	<input type="button" id="nao" value="Não" onclick="location='/'" class="left"/>
</form>
