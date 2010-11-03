<?php if (!empty($visitors)): ?>
	<ul data-role="listview" role="listbox" data-inset="true" data-theme="c" data-dividertheme="b">
		<li data-role="list-divider">Quem esteve aqui</li>
		
	<?php foreach ($visitors as $visitor) { ?>
		<li tabindex="0" class="ui-li-has-thumb ui-li ui-btn ui-btn-up-c" data-theme="c">
			<div class="ui-btn-text">
				<img width="64" height="64" src="<?php echo ($visitor->visitor->user->photo ? $visitor->visitor->user->photo : 'http://www.apontador.com.br/apontador_v8/images/accounts/user64.gif'); ?>" class="ui-li-thumb">
				<h3 class="ui-li-heading"><a href="<?php echo 'http://www.apontador.com.br/profile/' . $visitor->visitor->user->id . '.html'; ?>" class="ui-link-inherit"><?php echo $visitor->visitor->user->name; ?></a></h3>
				<p class="ui-li-desc">(<?php echo $visitor->visitor->visits . ($visitor->visitor->visits == 1 ? ' visita' : ' visitas'); ?>)
				em <?php echo date("d/m H:i", strtotime($visitor->visitor->last_visit)); ?></p>
			</div>
		</li>
	<?php }?>
	</ul>
<?php else: ?>
	<p>Ninguém registrou a presença neste local.</p>
<?php endif; ?>
