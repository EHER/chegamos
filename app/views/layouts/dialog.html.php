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
		</div>
		<div data-role="content">
			<?php echo $this->content(); ?>
		</div>
		<div data-role="footer">&nbsp</div>
	</div>
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
