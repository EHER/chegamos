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
	<title>Chegamos! <?php if(!empty($title)) echo "- " . $title; ?></title>
	<meta name="google-site-verification" content="DHlJPavykQLKD9wWywvKhr_t04fToqn-wK4WPdODQcQ" />
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.css" />
	<link rel="shortcut icon" href="<?php echo ROOT_URL ?>favicon.ico">
	<script src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.js"></script>
	<script>
		$.mobile.page.prototype.options.backBtnText = "Voltar";
	</script>
</head>
<body>
	<div data-role="page" data-theme="b" id="jqm-home">
		<div data-role="header"> 
			<h1><?php echo $this->html->link('Chegamos!', '/', array("rel" => "nofollow")); ?></h1>
			<?php echo $this->html->link('Config.', '/settings', array("rel" => "nofollow","data-icon"=>"gear","class"=>"ui-btn-right", "data-transition"=>"slideup")); ?>
			<form method="GET" action="<?php echo ROOT_URL; ?>places/search" style="text-align: center; width:100%">
				<input type="text" id="name" name="name" value="<?php echo (isset($_GET['name']) ? $_GET['name'] : '');?>" style="display: inline; width: 70%;">
				<input type="submit" value="Buscar" data-inline="true" style="display: inline;">
			</form>
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
						CEP: <?php echo $zipcode; ?>
					<?php endif; ?>

					<?php if (!empty($cityState)): ?>
						<?php echo $cityState; ?>
					<?php endif; ?>

					<?php if (!empty($geocode) && empty($zipcode) && empty($cityState)) { ?>
						<?php echo$geocode; ?>
					<?php } else if (!empty($lat) and !empty($lng)) { ?>
					(<?php echo $lat; ?>, <?php echo $lng; ?>)
					<?php } ?>
				</b>
			</p>
			<p>
				<?php if (!isset($hideWhereAmI) || !$hideWhereAmI) { ?>
					<a data-inline="true" href="<?php echo ROOT_URL; ?>places/checkin" data-role="button" data-theme="b">alterar</a>
				<?php } ?>
				<a data-inline="true" onclick="javascript:getUserLocation()" href="#" data-role="button" data-theme="b">detectar</a>
				<?php if (!empty($showCheckin) && $showCheckin) { ?>
				<a data-inline="true" href="<?php echo ROOT_URL . 'places/checkin?placeId=' . $placeId ?>" data-role="button" data-theme="b" rel="external">check-in</a>
				<?php } ?>

			</p>
			<a rel="nofollow" style="float:right;" href="#" onclick="$.mobile.silentScroll();">topo ↑</a>
		</div>
		<div data-role="footer">
			<h2><a href="http://api.apontador.com.br/" target="_blank" rel="external">Apontador API</a></h2>
		</div>
	</div>
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
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-19798490-1']);
  _gaq.push(['_setDomainName', 'none']);
  _gaq.push(['_setAllowLinker', true]);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
