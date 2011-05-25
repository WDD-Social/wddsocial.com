<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ForgotPasswordView implements \Framework5\IView {		
	
	public function render($options = null) {
		return <<<HTML

					<form action="/forgot-password" method="post" class="small">
						<p>{$options['intro']}</p>
						<p class="error"><strong>{$options['error']}</strong></p>
						<fieldset>
							<label for="email">Email</label>
							<input type="email" name="email" id="email" value="{$_POST['email']}" autofocus />
						</fieldset>
						<p class="helper-link"><a href="/signin" title="Already know your password? Sign In here." tabindex="1000">Sign In</a></p>
						<input type="submit" name="submit" value="Help Me!" />
					</form>
HTML;
	}
}