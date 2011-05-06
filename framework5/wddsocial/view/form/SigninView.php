<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class SignInView implements \Framework5\IView {		
	
	public static function render($options = null) {
		$root = \Framework5\Request::root_path();
		return <<<HTML

					<form action="{$root}signin" method="post" class="small">
						<p class="error"><strong>{$options['error']}</strong></p>
						<fieldset>
							<label for="email">Email</label>
							<input type="email" name="email" id="email" value="{$_POST['email']}" autofocus />
						</fieldset>
						<fieldset>
							<label for="password" class="helper-link">Password</label>
							<p class="helper-link"><a href="#" title="Did you forget your password?" tabindex="1000">Forgot?</a></p>
							<input type="password" name="password" id="password" />
						</fieldset>
						<p class="helper-link"><a href="{$root}signup" title="Not yet a member of WDD Social? Sign up here." tabindex="1000">Not yet a member?</a></p>
						<input type="submit" name="submit" value="Sign In" />
					</form>
HTML;
	}
}