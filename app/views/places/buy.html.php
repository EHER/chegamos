<?php use app\controllers\OauthController;?>

<h2 style="margin:0;">
    <?php echo $this->html->link($place->getName(), $place->getPlaceUrl(), array('rel'=>'nofollow', 'class'=>'fn org url')); ?>
</h2>

<p>Quer uma forma de divulgar a sua empresa na internet e atrair mais clientes para o seu negócio?<br/>
    Preencha o formulário com seus dados de contato e um consultor entrará em contato.</p>
<form action="https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8" method="post" id="fale_conosco" name="fale_conosco">
		<fieldset>
			<ul>
				<li>
                    <label for="last_name">Nome</label>
                    <span id="nome_error" class="erro_form" style="display: none;">É necessário informar seu nome.</span>
					<input type="text" value="<?php echo $place->getName();?>" id="last_name" class="text" name="last_name">
				</li>
                <li>
                    <label for="phone">Telefone</label>
					<input type="text" value="<?php echo $place->getPhone()->area . ' ' . $place->getPhone()->number;?>" class="tel text" id="phone" name="phone">
                </li>
				<li>
					<label for="email">E-mail</label>
					<span id="email_error" class="erro_form" style="display: none;">Seu e-mail não está correto.</span>
					<input type="text" value="" class="text" id="email" name="email">	
				</li>
			</ul>
			<input type="submit" value="Enviar solicitação" id="envia_form" class="bt_enviar">
			<input type="hidden" value="<?php echo $place->getName();?>" class="text" id="company" name="company">	
            <input type="hidden" value="<?php echo $place->getAddress()->getStreet() . ', ' .  $place->getAddress()->getNumber(); ?>" maxlength="150" class=" text" id="00NC00000051v0V" name="00NC00000051v0V">
            <input type="hidden" value="<?php echo $place->getAddress()->getCity()->getName();?>" maxlength="100" class="cidade text" id="00NC00000051v0p" name="00NC00000051v0p">
            <input type="hidden" value="<?php echo $place->getAddress()->getCity()->getState();?>"  id="00NC00000051ul6" name="00NC00000051ul6">
            <input type="hidden" value="Indicação feita pelo site Chegamos! http://chegamos.com/" id="description" name="description">
			<input type="hidden" value="<?php echo $place->getId();?>" id="lbsid" name="lbsid">
    		<input type="hidden" value="00DC0000000QLJt" name="oid">
    		<input type="hidden" value="Web (web to lead)" name="lead_source">
    		<input type="hidden" value="012C0000000GCdR" name="recordType">
    		<input type="hidden" value="<?php echo $place->getPlaceUrl(); ?>" name="retURL">
    		<input type="hidden" value="<?php echo $place->getPlaceUrl(); ?>" name="URL">
			
		</fieldset>
	</form>