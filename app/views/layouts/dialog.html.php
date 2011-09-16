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
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.css" />
    <script src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.js"></script>
	<link rel="shortcut icon" href="<?php echo ROOT_URL ?>favicon.ico">
	<meta property="fb:app_id" content="<?php echo FACEBOOK_AP_ID; ?>"/>
	<meta property="og:site_name" content="chegamos"/>
<?php echo (isset($meta)) ? $meta : '' ?>
	<meta name="google-site-verification" content="nSgmfqNOpud7XKqEtIzxAmHppP-oDqE3PGKwLLOeGss" />
</head>
<body>
	<div data-role="page" data-theme="<?php echo THEME_MAIN; ?>" id="jqm-home">
		<div data-role="header">
			<h1><?php echo $this->html->link('Chegamos!', '/', array("rel" => "nofollow")); ?></h1>
		</div>
		<div data-role="content">
			<?php echo $this->content(); ?>
		</div>
		<div data-role="footer">&nbsp</div>
	</div>
    <input type="hidden" id="rootUrl" value="<?php echo ROOT_URL;?>"/>
	<script src="<?php echo ROOT_URL ?>js/jquery.cookie.js"></script>
	<script src="<?php echo ROOT_URL ?>js/chegamos.js"></script>
</body>
</html>
