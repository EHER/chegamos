<p>Você deve fazer login com sua conta do <a href="http://www.apontador.com.br/">Apontador</a>.</p>
<form id="form_login" action="http://www.apontador.com.br/accounts/post_login.html" method="POST" >
	<input id="callback" type="hidden" name="callback" value="<?php echo $callbackUrl; ?>"/>
	<label for="user_email_login">E-mail</label>
	<input id="user_email_login" type="text" name="user[email]"/>
	<br/>
	<label for="user_password_login">Senha</label>
	<input id="user_password_login" type="password" name="user[password]"/>
	<br/>
	<input type="submit" value="Login">
</form>
