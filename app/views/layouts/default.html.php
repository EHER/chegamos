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
	<?php echo $this->html->style(array('debug', 'lithium')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body class="app">
	<div id="container">
		<a href="http://github.com/EHER/Apontador-Jr" target="_blank"><img style="position: absolute; top: 0; right: 0; border: 0;" src="http://s3.amazonaws.com/github/ribbons/forkme_right_green_007200.png" alt="Fork me on GitHub" /></a>
		<div id="header">
			<h1><?php echo $this->html->link('Apontador Jr', '/'); ?></h1>
			<h2>Usando a <a href="http://api.apontador.com.br/" target="_blank">Apontador API</a></h2>
		</div>
		<div id="content">
			<?php echo $this->content(); ?>
		</div>
		<br/>
		<div id="footer">
			<p>Powered by <?php echo $this->html->link('Lithium', 'http://li3.rad-dev.org'); ?>.</p>
		</div>

	</div>
</body>
</html>
