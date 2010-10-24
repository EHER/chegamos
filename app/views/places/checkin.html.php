<h3>Onde você está?</h3>
<span>
    <?php /*
    <form method="GET" style="width: 180px;">
        <fieldset>
            <label for="placeId">LBSID:</label>
            <input type="text" id="placeId" name="placeId" value="<?= $placeId; ?>" style="width: 100px;">
        </fieldset>
        <input type="submit" value="Estou aqui">
    </form>
     */?>
    <form method="GET" style="width: 180px;">
        <fieldset>
            <label for="cep">CEP:</label>
            <input type="text" id="cep" name="cep" value="<?= $zipcode; ?>" style="width: 100px;">
        </fieldset>
        <input type="submit" value="Estou aqui">
    </form>
    <form method="GET" style="width: 180px;">
        <fieldset>
            <label for="cityState">Cidade, UF:</label>
            <input type="text" id="cityState" name="cityState" value="<?= $cityState; ?>" style="width: 100px;">
        </fieldset>
        <input type="submit" value="Estou aqui">
    </form>
    <?php /*
    <form method="GET" style="width: 180px;">
        <fieldset>
            <label for="lat">Latitude:</label>
            <input type="text" id="lat" name="lat" value="<?= $lat; ?>" style="width: 100px;">
            <label for="lng">Longitude:</label>
            <input type="text" id="lng" name="lng" value="<?= $lng; ?>" style="width: 100px;">
        </fieldset>
        <input type="submit" value="Estou aqui">
    </form>
     */?>
</span>
