<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class NewPasswordView implements \Framework5\IView {		
	
	public function render($options = null) {
		$intro = "<p>{$options['intro']}</p>";
		$error = "<p class=\"error\"><strong>{$options['error']}</strong></p>";
		
		return <<<HTML

					<form action="/new-password" method="post" class="small">
						{$intro}
						{$error}
						<fieldset>
							<label for="email">Email</label>
							<input type="email" name="email" id="email" value="{$_POST['email']}" autofocus />
							<label for="password">New Password</label>
							<input type="password" name="password" id="password" value="{$_POST['password']}" />
						</fieldset>
						<p class="helper-link"><a href="/signin" title="Already know your password? Signin here." tabindex="1000">Signin</a></p>
						<input type="submit" name="submit" value="Sign In" />
					</form>
HTML;
	}
}