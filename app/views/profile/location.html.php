<form method="GET">
	<fieldset>
		<label for="cep">CEP:</label> <input type="text" id="cep" name="cep">
	</fieldset>
	<input type="submit" value="Estou aqui">
</form>
<form method="GET">
	<fieldset>
		<label for="cityState">Cidade, UF:</label> <input type="text" id="cityState" name="cityState">
	</fieldset>
	<input type="submit" value="Estou aqui">
</form>
<div data-role="fieldcontain" id="autoDetectContainer" class="ui-controlgroup-controls" style="display: none;">
	<fieldset data-role="controlgroup" data-type="horizontal" data-role="fieldcontain">
		<label>Detectar localização automaticamente:</label>
		<label for="autoDetect_on">&nbsp;&nbsp;Ligado</label>
		<input data-theme="c" class='autoDetect' type="radio" name="autoDetect" id="autoDetect_on" value="on" <?php echo (!isset($_COOKIE['disableAutoDetect']) ? ' checked="checked"' : '');?> />
		<label for="autoDetect_off">&nbsp;&nbsp;Desligado</label>
		<input data-theme="c" class='autoDetect' type="radio" name="autoDetect" id="autoDetect_off" value="off" <?php echo (isset($_COOKIE['disableAutoDetect']) ? ' checked="checked"' : '');?> />
	</fieldset>
</div>
<form method="GET" action="<?php echo ROOT_URL;?>">
	<button type="submit">Salvar Configurações</button>
</form>