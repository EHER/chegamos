<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
    <li data-role="list-divider">Configurações de Conta</li>
    <li data-role="list-divider">
        <?php if ($apontador['logged']) { ?>
            <img src="<?php echo STATIC_URL; ?>/img/ok.png">
    			Apontador:
            <a href="<?php echo ROOT_URL; ?>/oauth/logout/apontador" rel="external">Desconectar (<?php echo $apontador['name']; ?>)</a>
        <?php } else { ?>
            <img src="<?php echo STATIC_URL; ?>/img/nok.png">
    			Apontador:
            <a href="<?php echo ROOT_URL; ?>/oauth/authorize/apontador" rel="external">Conectar</a>
        <?php } ?>
    </li>
    <li data-role="list-divider">

        <?php if ($foursquare['logged']) { ?>
            <img src="<?php echo STATIC_URL; ?>/img/ok.png">
    			Foursquare:
            <a href="<?php echo ROOT_URL; ?>/oauth/logout/foursquare" rel="external">Desconectar (<?php echo $foursquare['name']; ?>)</a>
        <?php } else { ?>
            <img src="<?php echo STATIC_URL; ?>/img/nok.png">
    			Foursquare:
            <a href="<?php echo ROOT_URL; ?>/oauth/authorize/foursquare" rel="external">Conectar</a>
        <?php } ?>
    </li>
    <li data-role="list-divider">
        <?php if ($twitter['logged']) { ?>
            <img src="<?php echo STATIC_URL; ?>/img/ok.png">
    			Twitter:
            <a href="<?php echo ROOT_URL; ?>/oauth/logout/twitter" rel="external">Desconectar (@<?php echo $twitter['name']; ?>)</a>
        <?php } else { ?>
            <img src="<?php echo STATIC_URL; ?>/img/nok.png">
    			Twitter:
            <a href="<?php echo ROOT_URL; ?>/oauth/authorize/twitter" rel="external">Conectar</a>
        <?php } ?>
    </li>
    <li data-role="list-divider">
        <?php if ($facebook['logged']) { ?>
            <img src="<?php echo STATIC_URL; ?>/img/ok.png">
    			Facebook:
            <a href="<?php echo ROOT_URL; ?>/oauth/logout/facebook" rel="external">Desconectar (<?php echo $facebook['name']; ?>)</a>
        <?php } else { ?>
            <img src="<?php echo STATIC_URL; ?>/img/nok.png">
    			Facebook:
            <a href="<?php echo ROOT_URL; ?>/oauth/authorize/facebook" rel="external">Conectar</a>
        <?php } ?>
    </li>
    <li data-role="list-divider">
        <?php if ($orkut['logged']) { ?>
            <img src="<?php echo STATIC_URL; ?>/img/ok.png">
    			Orkut:
            <a href="<?php echo ROOT_URL; ?>/oauth/logout/orkut" rel="external">Desconectar (<?php echo $orkut['name']; ?>)</a>
        <?php } else { ?>
            <img src="<?php echo STATIC_URL; ?>/img/nok.png">
    			Orkut:
            <a href="<?php echo ROOT_URL; ?>/oauth/authorize/orkut" rel="external">Conectar</a>
        <?php } ?>
    </li>
</ul>
<form method="GET" action="<?php echo ROOT_URL; ?>">
    <button type="submit">Salvar Configurações</button>
</form>

