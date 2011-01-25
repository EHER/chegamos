<?php use \app\models\Deal; ?>
<?php use app\models\DealList; ?>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Ofertas por perto</li>
	<?php if ($dealsList instanceof DealList) { ?>
		<?php foreach ($dealsList->getItems() as $deal) { ?>
		    <li class="ui-li-has-thumb ui-btn ui-btn-icon-right ui-li ui-btn-down-c ui-btn-up-c">
				<br/>
				<p class="ui-li-desc" style="white-space:normal;">
					<img width="84" height="84" src="<?php echo ($deal->getImageUrl() ? $deal->getImageUrl() : 'http://www.apontador.com.br/apontador_v8/images/accounts/user64.gif'); ?>" class="ui-li-thumb">
					<?php echo $this->html->link($deal->getTitle(), "/deals/show/" . $deal->getId() . ""); ?>
				</p>
			</li>
		<?php } ?>
	<?php } else { ?>
	<li>
		Não encontrei ofertas por aqui<br/>
		Tente novamente amanhã
	</li>
	<?php } ?>
</ul>
