<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html xmlns:og="http://ogp.me/ns#">
<head>
	<meta property="og:site_name" content="chegamos"/>
	<meta property="fb:app_id" content="<?php echo FACEBOOK_AP_ID; ?>"/>
<?php echo (isset($meta)) ? $meta : '' ?>
	<meta name="google-site-verification" content="DHlJPavykQLKD9wWywvKhr_t04fToqn-wK4WPdODQcQ" />
	<?php echo $this->html->charset();?>

	<title>Chegamos! <?php if(!empty($title)) echo "- " . $title; ?></title>
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.css" />
	<link rel="shortcut icon" href="<?php echo ROOT_URL ?>favicon.ico">
	<script src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.js"></script>
	<script src="<?php echo ROOT_URL ?>js/jquery.cookie.js"></script>
	<script>
		$.mobile.page.prototype.options.backBtnText = "Voltar";
	</script>
</head>
<body>
	<div data-role="page" data-theme="b" id="jqm-home">
		<div data-role="header"> 
			<h1><?php echo $this->html->link('Chegamos!', '/', array("rel" => "external")); ?></h1>
			<?php echo $this->html->link('Config.', '/settings', array("rel" => "nofollow","data-icon"=>"gear","class"=>"ui-btn-right", "data-transition"=>"slideup")); ?>

			<form method="GET" action="<?php echo ROOT_URL; ?>places/search" style="text-align: center; width:100%">
				<input type="text" id="name" name="name" value="<?php echo (isset($_GET['name']) ? $_GET['name'] : '');?>" style="display: inline; width: 70%;">
				<input type="submit" value="Buscar" data-inline="true" style="display: inline;">
			</form>
		</div>
		<div data-role="content">
			<?php echo $this->content(); ?>
		</div>
		<div data-role="footer" style="text-align:center">
				<a href="<?php echo ROOT_URL; ?>places/checkin" rel="external" id="ondeEstou">
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
var intervalo = window.setInterval(function() {
	if($.cookie('disableAutoDetect') === null) {
		getUserLocation();
	}
}, 5000);

updateLocation = function(lat, lng) {
	$.get('<?php echo ROOT_URL ?>places/checkin', {'lat': lat, 'lng': lng, 'type' : 'json'},
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
