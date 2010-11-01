<h3>Avaliações</h3>
<ul>
	<li><?php echo $this->html->link("Voltar", "/places/show/".$placeId); ?></li>
</ul>
<?php if ($reviews and $reviews->place->result_count): ?>
        <?php foreach ($reviews->place->reviews as $review): ?>
	    <ul>
            <li>
				<span class="content">
					<strong>
						<a href="http://www.apontador.com.br/local/review/<?php echo $placeId; ?>/<?php echo $review->review->id; ?>.html" target="_blank">
						<?php echo $review->review->created->user->name; ?></a>:
					</strong>
					<?php echo $review->review->content; ?>
				</span>
			</li>
	    </ul>
        <?php endforeach; ?>
<?php else: ?>
    <p>Esse local ainda não foi avaliado.</p>
<?php endif; ?>

<h3>Deixe sua opinião</h3>
<span>
    <form method="GET" style="width: 180px;">
        <fieldset>

			<label for="rating">Nota:</label>
			<select id="rating" name="rating">
			  <option></option>
			  <option value="1">Péssimo</option>
			  <option value="2">Ruim</option>
			  <option value="3">Regular</option>
			  <option value="4">Bom</option>
			  <option value="5">Excelente</option>
			</select>
            <label for="content">Avaliação:</label>
            <textarea id="content" name="content"></textarea>
        </fieldset>
        <input type="submit" value="Publicar">
    </form>
</span>
