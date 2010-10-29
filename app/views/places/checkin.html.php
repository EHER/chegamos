<h3>Onde você está?</h3>
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
    <form method="GET" style="width: 180px;">
            Detectar onde estou
            <input type="hidden" name="detect" value="true">
        <input type="submit" onclick="getUserLocation();return false;" value="Detectar">
    </form>
    <form method="GET" style="width: 180px;">
        <fieldset>
            <label for="cep">CEP:</label>
            <input type="text" id="cep" name="cep" value="" style="width: 100px;">
        </fieldset>
        <input type="submit" value="Estou aqui">
    </form>
    <form method="GET" style="width: 180px;">
        <fieldset>
            <label for="cityState">Cidade, UF:</label>
            <input type="text" id="cityState" name="cityState" value="" style="width: 100px;">
        </fieldset>
        <input type="submit" value="Estou aqui">
    </form>
    <?php /*
    <form method="GET" style="width: 180px;">
        <fieldset>
            <label for="lat">Latitude:</label>
            <input type="text" id="lat" name="lat" value="" style="width: 100px;">
            <label for="lng">Longitude:</label>
            <input type="text" id="lng" name="lng" value="" style="width: 100px;">
        </fieldset>
        <input type="submit" value="Estou aqui">
    </form>
     */?>
</span>
<script type="text/javascript">
getUserLocation = function() {
	navigator.geolocation.getCurrentPosition(showpos);
}
showpos = function(position){
	lat=position.coords.latitude
	lon=position.coords.longitude
	location.href = location.href + "?lat=" + lat + '&lng='+lon;
}
</script>
