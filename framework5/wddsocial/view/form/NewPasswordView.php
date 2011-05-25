<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class NewPasswordView implements \Framework5\IView {		
	
	public function render($options = null) {
		return <<<HTML

					<form action="/new-password/{$options['code']}" method="post" class="small">
						<p>{$options['intro']}</p>
						<p class=\"error\"><strong>{$options['error']}</strong></p>
						<fieldset>
							<label for="email">Email</label>
							<input type="email" name="email" id="email" value="{$_POST['email']}" autofocus />
							<label for="password">New Password</label>
							<input type="password" name="password" id="password" />
						</fieldset>
						<input type="submit" name="submit" value="Sign In" />
					</form>
HTML;
	}
}