<h2><?php echo $place->place->name; ?></h2>
<p><?php echo $place->place->description; ?></p>

<?php
/*
  <p><?php echo $place->place->click_count; ?></p>
  <p><?php echo $place->place->review_count; ?></p>
  <p><?php echo $place->place->average_rating; ?></p>
  <p><?php echo $place->place->thumbs->total; ?></p>
  <p><?php echo $place->place->thumbs->up; ?></p>
 */
?>
<ul>
	<li>
		<?php
		echo $this->html->link(
				"Veja no Apontador",
				"http://www.apontador.com.br/local/poi/" . $place->place->id . ".html",
				array("target" => "_blank")
		); ?>
	</li>
	<li>
		<?php echo $this->html->link("Estou aqui", "/places/checkin/" . $place->place->id . ""); ?>
	</li>
</ul>
