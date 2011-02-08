<span>
    <?php /*
    <form method="GET" style="width: 180px;">
        <fieldset>
            <label for="placeId">LBSID:</label>
            <input type="text" id="placeId" name="placeId" value="" style="width: 100px;">
        </fieldset>
        <input type="submit" value="Estou aqui">
    </form>
     */?>
    <form method="GET" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <fieldset>
            <label for="cep">CEP:</label>
            <input type="text" id="cep" name="cep">
        </fieldset>
        <input type="submit" value="Estou aqui">
    </form>
    <form method="GET" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <fieldset>
            <label for="cityState">Cidade, UF:</label>
            <input type="text" id="cityState" name="cityState">
        </fieldset>
        <input type="submit" value="Estou aqui">
    </form>
    <div id="autoDetectContainer" style="display:none;">
	    <label for="autoDetect">Detectar automaticamente:</label>
		<select name="autoDetect" id="autoDetect" data-role="slider">
			<option value="off"<?php echo (isset($_COOKIE['disableAutoDetect']) ? ' selected="selected"' : '');?>>Desligado</option>
			<option value="on"<?php echo (!isset($_COOKIE['disableAutoDetect']) ? ' selected="selected"' : '');?>>Ligado</option>
		</select>
	</div>
</span>

<script type="text/javascript">
	$('#autoDetectContainer').show();

	$('#autoDetect').change(function() {
		if ($('#autoDetect').val() == 'off') {
			$.cookie('disableAutoDetect', $('#autoDetect').val(), {'path' : '/'});
		} else {
			$.cookie('disableAutoDetect', null, {'path' : '/'});
		}
	});
</script>