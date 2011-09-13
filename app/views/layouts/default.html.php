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
	<?php echo $this->html->charset();?>
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.css" />
    <script src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.js"></script>
    <title>Chegamos! <?php if(!empty($title)) echo "- " . $title; ?></title>
	<link rel="shortcut icon" href="<?php echo ROOT_URL ?>favicon.ico">
	<meta property="fb:app_id" content="<?php echo FACEBOOK_AP_ID; ?>"/>
	<meta property="og:site_name" content="chegamos"/>
<?php echo (isset($meta)) ? $meta : '' ?>
	<meta name="google-site-verification" content="nSgmfqNOpud7XKqEtIzxAmHppP-oDqE3PGKwLLOeGss" />
</head>
<body>
	<div data-role="page" data-theme="b" id="jqm-home">
		<div data-role="header" data-theme="b"> 
			<h1>
				<?php echo $this->html->link('Chegamos!', '/', array("rel" => "external", 'data-role' => "button", "data-icon" => "home")); ?>
			</h1>
			<?php echo $this->html->link('Config.', '/settings', array("rel" => "nofollow","data-icon"=>"gear","class"=>"ui-btn-right", "data-transition"=>"slideup")); ?>

			<form method="GET" action="<?php echo ROOT_URL; ?>places/search" style="text-align: center; width:100%">
				<input type="text" id="q" name="q" value="<?php echo (isset($_GET['q']) ? $_GET['q'] : '');?>" style="display: inline; width: 60%;">
				<input type="submit" value="Buscar" data-inline="true" style="display: inline;"  data-icon="search">
			</form>
		</div>
		<div data-role="content">
			<?php echo $this->content(); ?>
		</div>
		<div data-role="footer" data-theme="b" style="text-align:center" data-position="fixed">
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
	<script src="<?php echo ROOT_URL ?>js/jquery.cookie.js"></script>
	<script src="<?php echo ROOT_URL ?>js/chegamos.js"></script>
</body>
</html>
