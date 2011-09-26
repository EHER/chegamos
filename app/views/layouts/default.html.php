<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
use app\models\Point;
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
		<?php echo ROOT_URL; ?>profile/location
		<div data-role="footer" data-theme="b" style="text-align:center" data-position="fixed">
				<a href="<?php echo ROOT_URL; ?>/profile/location" rel="external" id="ondeEstou">
					<?php if(isset($location)){ ?>
						<?php echo $location->getAddress()->toOneLine(); ?>
						<!-- 
						<?php if($location->getPoint() instanceof Point){ ?>
							(<?php echo $location->getPoint(); ?>)
						<?php } ?>
						 -->
					<?php } else {?>
						Clique para selecionar sua localização
					<?php } ?>
				</a>
		</div>
	</div>
    <input type="hidden" id="rootUrl" value="<?php echo ROOT_URL;?>"/>
<?php if(!defined('LIGHT_VERSION') || LIGHT_VERSION === false) { ?>	    
    <script src="<?php echo STATIC_URL ?>min/?g=js_body"></script>
<?php } ?>
</body>
</html>
