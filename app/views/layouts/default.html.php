<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html xmlns:og="http://ogp.me/ns#"<?php echo empty($abmType) ? '' : ' xmlns:"'.$abmType.'="http://www.abmeta.org/ns#"';?>>
<head<?php echo empty($abmType) ? '' : ' typeof="'.$abmType.':'.ucwords($abmType).'"';?>>
	<meta property="fb:app_id" content="<?php echo FACEBOOK_AP_ID; ?>"/>
	<meta property="og:site_name" content="chegamos"/>
<?php echo (isset($meta)) ? $meta : '' ?>
	<meta name="google-site-verification" content="nSgmfqNOpud7XKqEtIzxAmHppP-oDqE3PGKwLLOeGss" />
	<?php echo $this->html->charset();?>

	<title>Chegamos! <?php if(!empty($title)) echo "- " . $title; ?></title>
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.5.2.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.0a4.1/jquery.mobile-1.0a4.1.min.js"></script>
	<link rel="shortcut icon" href="<?php echo ROOT_URL ?>favicon.ico">
	<script src="<?php echo ROOT_URL ?>js/jquery.cookie.js"></script>
	<script>
		$.mobile.page.prototype.options.backBtnText = "Voltar";
	</script>
</head>
<body>
	<div data-role="page" data-theme="b" id="jqm-home">
		<div data-role="header"> 
			<h1>
				<?php echo $this->html->link('Chegamos!', '/', array("rel" => "external", 'data-role' => "button")); ?>
			</h1>
			<?php echo $this->html->link('Config.', '/settings', array("rel" => "nofollow","data-icon"=>"gear","class"=>"ui-btn-right", "data-transition"=>"slideup")); ?>

			<form method="GET" action="<?php echo ROOT_URL; ?>places/search" style="text-align: center; width:100%">
				<input type="text" id="name" name="name" value="<?php echo (isset($_GET['name']) ? $_GET['name'] : '');?>" style="display: inline; width: 70%;">
				<input type="submit" value="Buscar" data-inline="true" style="display: inline;">
			</form>
		</div>
		<div data-role="content">
			<?php echo $this->content(); ?>
		</div>
		<div data-role="footer" style="text-align:center" data-position="fixed">
				<a href="<?php echo ROOT_URL; ?>profile/location" rel="external" id="ondeEstou">
					<?php if (!empty($zipcode)): ?>
						CEP: <?php echo $zipcode; ?>
					<?php endif; ?>

					<?php if (!empty($cityState)): ?>
						<?php echo $cityState; ?>
					<?php endif; ?>

					<?php if (!empty($geocode) && empty($zipcode) && empty($cityState)) { ?>
						<?php echo $geocode->toOneLine(); ?>
					<?php } else if (!empty($lat) and !empty($lng)) { ?>
					(<?php echo $lat; ?>, <?php echo $lng; ?>)
					<?php } ?>
				</a>
		</div>
	</div>
	
<script type="text/javascript">

var updateOnTimeout = function() {
	var timeout = 1000 * 60 * 10;
	var lastUpdate = $.cookie('lastLocationUpdate');
	var currentTime = new Date();
	var now = currentTime.getTime();

	if(lastUpdate === null || now > lastUpdate*1 + timeout*1) {
		$.cookie('lastLocationUpdate', now);
		getUserLocation();
	}
}

var intervalo = window.setInterval(function() {
	if($.cookie('disableAutoDetect') === null) {
		updateOnTimeout();
	}
}, 10000);

$('#name').click();

updateLocation = function(lat, lng) {
	$.get('<?php echo ROOT_URL ?>profile/location', {'lat': lat, 'lng': lng, 'type' : 'json'},
		function(data) {
			if (data.success === true) {
				var addressData = [
				   	data.checkinData.street,
				   	data.checkinData.district,
				   	data.checkinData.city,
				   	data.checkinData.state 
				];

				$('#ondeEstou').fadeOut();
				$('#ondeEstou span').html(addressData.filter(String).join(', '));
				$('#ondeEstou').fadeIn();
			}
		}
	);
}

getUserLocation = function() {
	navigator.geolocation.getCurrentPosition(
		function(position){
			lat=position.coords.latitude;
			lng=position.coords.longitude;
			updateLocation(lat,lng);
		}
	);
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
