<form id=+"form_login" action="http://www.apontador.com.br/accounts/login.html" method="POST" >
	<label for="callback">Callback</label>
	<input id="callback" type="text" name="callback" value="<?=$oauthCallbackUrl; ?>"/>
	<br/>
	<label for="user_email_login">E-mail</label>
	<input id="user_email_login" type="text" name="user[email]"/>
	<br/>
	<label for="user_password_login">Senha</label>
	<input id="user_password_login" type="password" name="user[password]"/>
	<br/>
	<input type="submit" value="Login">
</form>
