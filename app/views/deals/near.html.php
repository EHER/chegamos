<?php use \app\models\Deal; ?>
<?php use app\models\DealList; ?>
<ul data-role="listview" data-inset="true" data-theme="<?php echo THEME_LIST; ?>" data-dividertheme="<?php echo THEME_MAIN; ?>">
	<li data-role="list-divider">Ofertas por perto</li>
	<?php if ($dealsList instanceof DealList) { ?>
		<?php foreach ($dealsList->getItems() as $deal) { ?>
		    <li class="ui-li-has-thumb ui-btn ui-btn-icon-right ui-li ui-btn-down-c ui-btn-up-c">
				<?php if($deal->getImageUrl()) { ?>
					<img style="float:left; margin: 0 5px 5px 0" title="<?php echo $deal->getTitle(); ?>" alt="<?php echo $deal->getTitle(); ?>" width="84" height="84" src="<?php echo $deal->getImageUrl(); ?>">
				<?php } ?>
				<p style="white-space: normal; margin-top:0px;" class="ui-li-desc" style="white-space:normal;">
					<?php echo $this->html->link($deal->getTitle(), $deal->getUrl(), array('rel'=>'external', 'target' => '_blank')); ?>
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