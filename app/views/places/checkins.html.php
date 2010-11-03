<h2>Quem esteve aqui</h2>

<ul>
	<li>
		<?php echo $this->html->link("Voltar", "/places/show/".$placeId); ?>
	</li>
</ul>
<?php if (!empty($visitors)): ?>
	<ul>
	<?php foreach ($visitors as $visitor) { ?>
		<li>
			<a href="<?php echo 'http://www.apontador.com.br/profile/' . $visitor->visitor->user->id . '.html'; ?>">
				<?php echo $visitor->visitor->user->name; ?>
			</a>
			(<?php echo $visitor->visitor->visits . ($visitor->visitor->visits == 1 ? ' visita' : ' visitas'); ?>)
			em <?php echo date("d/m H:i", strtotime($visitor->visitor->last_visit)); ?>
		</li>
	<?php }?>
	</ul>
<?php else: ?>
	<p>Ninguém registrou a presença neste local.</p>
<?php endif; ?>
