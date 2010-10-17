<?php if($success): ?>
<p>Place successfully saved.</p>
<?php endif; ?>
<?=$this->form->create();?>
    <?=$this->form->field('name');?>
    <?=$this->form->submit('Save');?>
<?=$this->form->end();?>

