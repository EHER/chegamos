<h2 style="margin:0;">
	<?php echo $this->html->link($place->getName(), "/places/show/" . $place->getId()); ?>
	<?php if($place->getAverageRatingString()) { ?>
		<small>(<?php echo $place->getAverageRatingString() ?>)</small>
	<?php } ?>
</h2>
<p><?php echo $place->getAddress(); ?></p>
<p>
	<?php if(!empty($place->getPhone()->number)) echo 'Fone:'; ?>
	<?php if(!empty($place->getPhone()->country)) echo '+' . $place->getPhone()->country; ?>
	<?php if(!empty($place->getPhone()->area)) echo '(' . $place->getPhone()->area . ')'; ?>
	<?php if(!empty($place->getPhone()->number)) echo $place->getPhone()->number; ?>
</p>

<?php if (!empty ($reviewId)) { ?>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<?php foreach ($reviews->place->reviews as $review): ?>
		<li data-role="list-divider">Avaliação de <?php echo $review->review->created->user->name; ?></li>
		<li>
            <div class="ui-btn-text">
				<img style="padding-top:20px;" width="64" height="64" src="<?php echo ($review->review->created->user->photo_url ? $review->review->created->user->photo_url : 'http://www.apontador.com.br/apontador_v8/images/accounts/user64.gif'); ?>" class="ui-li-thumb" />
				<h3 class="ui-li-heading">
					<a href="<?php echo ROOT_URL . 'profile/show/' . $review->review->created->user->id; ?>">
						<?php echo $review->review->created->user->name; ?>
					</a>
				</h3>
			</div>
			<p>
			<div class="rate">
				<b style="width: <? echo $review->review->rating * 20;?>%;"></b>
			</div>
			em <?php echo date("d/m H:i", strtotime($review->review->created->timestamp)); ?></p>
			<p style="white-space:normal;"><?php echo $review->review->content; ?></p>
		</li>
		<?php endforeach; ?>
	</ul>
<?php } ?>

<?php if (empty($reviewId) and $reviews and $reviews->place->result_count) { ?>
	<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
		<li data-role="list-divider">Avaliações (<?php echo $reviews->place->result_count; ?>)</li>
        <?php foreach ($reviews->place->reviews as $review): ?>
            <li>
				<div style="padding-left:-20px;">
					<a href="<?php echo ROOT_URL . 'places/review/' . $review->review->place->id . '/' . $review->review->id; ?>">
						<h4><?php echo $review->review->created->user->name; ?></h4>
					</a>
					<p><?php echo $review->review->content; ?></p>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
<?php } else if (empty($reviewId)) { ?>
    <p>Esse local ainda não foi avaliado.</p>
<?php } ?>

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
