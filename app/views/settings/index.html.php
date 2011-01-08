<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Configurações de Conta</li>
	<li data-role="list-divider">
		<?php if ($apontadorLogged) { ?>
			<img src="<?php echo ROOT_URL;?>img/ok.png" alt="Apontador Conectado" class="ui-li-icon ui-li-thumb">
			<a href="<?php echo ROOT_URL;?>oauth/logout/apontador" rel="external">Apontador</a>
		<?php } else { ?>
			<img src="<?php echo ROOT_URL;?>img/nok.png" alt="Apontador Desconectado" class="ui-li-icon ui-li-thumb">
			<a href="<?php echo ROOT_URL;?>oauth/auth/apontador" rel="external">Apontador</a>
		<?php } ?>
	</li>
	<li data-role="list-divider">
		<?php if ($foursquareLogged) { ?>
			<img src="<?php echo ROOT_URL;?>img/ok.png" alt="Foursquare Conectado" class="ui-li-icon ui-li-thumb">
			<a href="<?php echo ROOT_URL;?>oauth/logout/foursquare" rel="external">Foursquare</a>
		<?php } else { ?>
			<img src="<?php echo ROOT_URL;?>img/nok.png" alt="Foursquare Desconectado" class="ui-li-icon ui-li-thumb">
			<a href="<?php echo ROOT_URL;?>oauth/auth/foursquare" rel="external">Foursquare</a>
		<?php } ?>
	</li>
	<li data-role="list-divider">
		<?php if ($twitterLogged) { ?>
			<img src="<?php echo ROOT_URL;?>img/ok.png" alt="Apontador Conectado" class="ui-li-icon ui-li-thumb">
			<a href="<?php echo ROOT_URL;?>oauth/logout/twitter" rel="external">Twitter</a>
		<?php } else { ?>
			<img src="<?php echo ROOT_URL;?>img/nok.png" alt="Apontador Desconectado" class="ui-li-icon ui-li-thumb">
			<a href="<?php echo ROOT_URL;?>oauth/auth/twitter" rel="external">Twitter</a>
		<?php } ?>
	</li>
	<li data-role="list-divider">
		<?php if ($facebookLogged) { ?>
			<img src="<?php echo ROOT_URL;?>img/ok.png" alt="Apontador Conectado" class="ui-li-icon ui-li-thumb">
			<a href="<?php echo ROOT_URL;?>oauth/logout/facebook" rel="external">Facebook</a>
		<?php } else { ?>
			<img src="<?php echo ROOT_URL;?>img/nok.png" alt="Apontador Desconectado" class="ui-li-icon ui-li-thumb">
			<a href="<?php echo ROOT_URL;?>oauth/auth/facebook" rel="external">Facebook</a>
		<?php } ?>
	</li>
</ul>

