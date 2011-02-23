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
    <div data-role="fieldcontain" id="autoDetectContainer" style="display:none;">
	    <fieldset data-role="controlgroup" data-type="horizontal" data-role="fieldcontain">
	    <label>Detectar localização automaticamente:</label>
		<label for="autoDetect_on">&nbsp;&nbsp;Ligado</label>
		<input class='autoDetect' type="radio" name="autoDetect" id="autoDetect_on" value="on" <?php echo (!isset($_COOKIE['disableAutoDetect']) ? ' checked="checked"' : '');?>>
		<label for="autoDetect_off">&nbsp;&nbsp;Desligado</label>
		<input class='autoDetect' type="radio" name="autoDetect" id="autoDetect_off" value="off" <?php echo (isset($_COOKIE['disableAutoDetect']) ? ' checked="checked"' : '');?>>
		</fieldset>
	</div>
</span>

<script type="text/javascript">
	$('#autoDetectContainer').show();

	$('.autoDetect').change(function() {
		if ($("input[name='autoDetect']:checked").val() == 'off') {
			$.cookie('disableAutoDetect', true, {'path' : '/'});
		} else {
			$.cookie('disableAutoDetect', null, {'path' : '/'});
			getUserLocation();
		}
	});
</script>