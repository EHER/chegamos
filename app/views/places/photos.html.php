<?php use app\models\PhotoList; ?>
<h2 style="margin:0;">
	<?php echo $this->html->link($place->getName(), "/places/show/" . $place->getId(), array('rel'=>'nofollow')); ?>
	<?php if($place->getAverageRatingString()) { ?>
		<small>(<?php echo $place->getAverageRatingString() ?>)</small>
	<?php } ?>
</h2>
<p><?php echo $place->getAddress(); ?></p>
<p>
	<?php if(!empty($place->getPhone()->number)) echo 'Fone:'; ?>
	<?php if(!empty($place->getPhone()->country)) echo '+' . $place->getPhone()->country; ?>
	<?php if(!empty($place->getPhone()->area)) echo '(' . $place->getPhone()->area . ')'; ?>
	<?php if(!empty($place->getPhone()->number)) echo $place->getPhone()->number; ?>
</p>

<?php if ($photos instanceof PhotoList && $photos->getNumFound() > 0) { ?>
	<?php foreach ($photos->getItems() as $k => $photo) { ?>
		<?php if ($k == $photoId) { ?>
			<div style="text-align:center;">
				<img src="<?php echo $photo->getUrl()?>" />
				<br />
				<?php if( !empty($photoId) ) { ?>
					<a data-inline="true" rel="external" href="<?php echo ROOT_URL . 'places/photos/' . $place->getId() . (($photoId > 1) ? '/' . ($photoId - 1) : ''); ?>" data-role="button" data-theme="b">&lt;&lt;</a>
				<?php } ?>
				<?php if( $photoId < $photos->getNumFound()-1 ) { ?>
					<a data-inline="true" rel="external" href="<?php echo ROOT_URL . 'places/photos/' . $place->getId() . '/' . ($photoId + 1); ?>" data-role="button" data-theme="b">&gt;&gt;</a>
				<?php } ?>
			</div>
		<?php } ?>
	<?php } ?>
<?php } else {?>
	<p>Esse local não possui fotos.</p>
<?php } ?>