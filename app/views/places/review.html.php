<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Deixe sua avaliação</li>
</ul>

	<form method="GET" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
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

<?php if ($reviews and $reviews->place->result_count): ?>
	<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
		<li data-role="list-divider">Avaliações (<?php echo $reviews->place->result_count; ?>)</li>
        <?php foreach ($reviews->place->reviews as $review): ?>
            <li>
            	<div class="ui-btn-text">
					<img width="64" height="64" src="<?php echo ($review->review->created->user->photo_url ? $review->review->created->user->photo_url : 'http://www.apontador.com.br/apontador_v8/images/accounts/user64.gif'); ?>" class="ui-li-thumb" />
					<h3 class="ui-li-heading">
						<a href="http://www.apontador.com.br/local/review/<?php echo $review->review->place->id; ?>/<?php echo $review->review->id; ?>.html">
							<?php echo $review->review->created->user->name; ?>
						</a>
					</h3>
				</div>
				<p>
				<div class="rate">
					<b style="width: <? echo $review->review->rating * 20;?>%;"></b>
				</div>
				em <?php echo date("d/m H:i", strtotime($review->review->created->timestamp)); ?></p>
				<p><?php echo $review->review->content; ?></p>
			</li>
		<?php endforeach; ?>
	</ul>
<?php else: ?>
    <p>Esse local ainda não foi avaliado.</p>
<?php endif; ?>
