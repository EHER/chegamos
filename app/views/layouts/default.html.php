<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Apontador Jr <?php echo $this->title(); ?></title>
	<?php //echo $this->html->style(array('debug', 'lithium')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.css" />
	<script src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.js"></script>
</head>
<body>
	<div data-role="page" data-theme="d" id="jqm-home">
		<div data-role="header"> 
			<h1><?php echo $this->html->link('Apontador Jr', '/'); ?></h1>
		</div>
		<div data-role="content">
			<?php echo $this->content(); ?>
			<p>&nbsp;</p>
			<p>Onde estou:</p>
			<p>
				<b>
					<?php
					if (!empty($placeName)) {
						echo $this->html->link($placeName, '/places/show/' . $placeId);
						echo '<br/>';
					}
					?>

					<?php if (!empty($zipcode)): ?>
						CEP: <?= $zipcode; ?>
					<?php endif; ?>

					<?php if (!empty($cityState)): ?>
						<?= $cityState; ?>
					<?php endif; ?>

					<?php if (!empty($geocode)) { ?>
						<?=$geocode; ?>
					<?php } else if (!empty($lat) and !empty($lng)) { ?>
					(<?= $lat; ?>, <?= $lng; ?>)
					<?php } ?>
				</b>
			</p>
			<p>
				<?php if (!isset($hideWhereAmI) || !$hideWhereAmI) { ?>
					<a data-inline="true" href="<?php echo ROOT_URL; ?>places/checkin" data-role="button" data-theme="b">alterar</a>
				<?php } ?>
				<a data-inline="true" onclick="javascript:getUserLocation()" href="#" data-role="button" data-theme="b">detectar</a>
				<?php if (!empty($showCheckin) && $showCheckin) { ?>
				<a data-inline="true" href="<?php echo ROOT_URL . 'places/checkin?placeId=' . $placeId ?>" data-role="button" data-theme="b">check-in</a>
				<?php } ?>

			</p>
			
		</div>
		<div data-role="footer">
			<h2><a href="http://api.apontador.com.br/" target="_blank">Apontador API</a></h2>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
	getUserLocation = function() {
		navigator.geolocation.getCurrentPosition(showpos);
	}
	showpos = function(position){
		lat=position.coords.latitude;
		lon=position.coords.longitude;
		location.href = '<?php echo ROOT_URL; ?>' + 'places/checkin?lat=' + lat + '&lng='+lon;
	}
</script>
