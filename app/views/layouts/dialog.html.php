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
    <title>Chegamos! <?php if(!empty($title)) echo "- " . $title; ?></title>
	<link rel="shortcut icon" href="<?php echo STATIC_URL ?>favicon.ico"/>
<?php if(!defined('LIGHT_VERSION') || LIGHT_VERSION === false) { ?>	
    <link rel="stylesheet" href="<?php echo STATIC_URL ?>min/?g=css"/>
    <script src="<?php echo STATIC_URL ?>min/?g=js_head"></script>
<?php } ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta property="fb:app_id" content="<?php echo FACEBOOK_AP_ID; ?>"/>
	<meta property="og:site_name" content="chegamos"/>
	<meta name="google-site-verification" content="nSgmfqNOpud7XKqEtIzxAmHppP-oDqE3PGKwLLOeGss" />
<?php echo (isset($meta)) ? $meta : '' ?>
</head>
<body>
	<div data-role="page" data-theme="b" id="jqm-home">
		<div data-role="header" data-theme="b">
			<h1>
                <?php echo $this->html->link('Chegamos!', '/', array("rel" => "nofollow")); ?>
            </h1>
		</div>
		<div data-role="content">
			<?php echo $this->content(); ?>
		</div>
		<div data-role="footer">&nbsp;</div>
	</div>
    <input type="hidden" id="rootUrl" value="<?php echo ROOT_URL;?>"/>
<?php if(!defined('LIGHT_VERSION') || LIGHT_VERSION === false) { ?>	    
    <script src="<?php echo STATIC_URL ?>min/?g=js_body"></script>
<?php } ?>
</body>
</html>
