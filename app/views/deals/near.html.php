<?php use \app\models\Deal; ?>
<?php use app\models\DealList; ?>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Ofertas por perto</li>
	<?php //var_dump($dealsList); ?>
	<?php if ($dealsList instanceof DealList) { ?>
		<?php foreach ($dealsList->getItems() as $deal) { ?>
		    <li>
				<span class="placename">
					<img width="64" height="64" src="<?php echo ($deal->getImageUrl() ? $deal->getImageUrl() : 'http://www.apontador.com.br/apontador_v8/images/accounts/user64.gif'); ?>" class="ui-li-thumb">
				</span>
				<br />
				<p class="ui-li-desc">
					<p>
						<?php echo $this->html->link($deal->getTitle(), "/deals/show/" . $deal->getId() . ""); ?>
					</p>
				</p>
			</li>
		<?php } ?>
	<?php } else { ?>
	<li>Não existem ofertas por perto.</li>
	<?php } ?>
</ul>
