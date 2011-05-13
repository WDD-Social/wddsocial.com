<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class SignupView implements \Framework5\IView {		
	
	public function render($options = null) {
		$html = <<<HTML

					<form action="/signup" method="post" enctype="multipart/form-data">
						<h1>Basics</h1>
						<p class="error"><strong>{$options['error']}</strong></p>
						<fieldset>
							<label for="first-name">First Name *</label>
							<input type="text" name="first-name" id="first-name" value="{$_POST['first-name']}" autofocus />
						</fieldset>
						<fieldset>
							<label for="last-name">Last Name *</label>
							<input type="text" name="last-name" id="last-name" value="{$_POST['last-name']}" />
						</fieldset>
						<fieldset>
							<label for="email">Email *</label>
							<input type="email" name="email" id="email" value="{$_POST['email']}" />
							<small>Used to sign in and for contacting you</small>
						</fieldset>
						<fieldset>
							<label for="full-sail-email">Full Sail Email *</label>
							<input type="email" name="full-sail-email" id="full-sail-email" value="{$_POST['full-sail-email']}" />
							<small>Used for account verification</small>
						</fieldset>
						<fieldset>
							<label for="password">Password *</label>
							<input type="password" name="password" id="password" />
							<small>6 or more characters</small>
						</fieldset>
						
						<h1>More Information</h1>
HTML;
		$html .= render('wddsocial.view.form.pieces.WDDSocial\UserTypeSelector', array('typeID' => 1, 'required' => true));
		$html .= <<<HTML

						<fieldset>
							<label for="avatar">Avatar</label>
							<input type="file" name="avatar" id="avatar" />
						</fieldset>
						<fieldset>
							<label for="hometown">Hometown</label>
							<input type="text" name="hometown" id="hometown" value="{$_POST['hometown']}" />
						</fieldset>
						<fieldset>
							<label for="birthday">Birthday</label>
							<input type="text" name="birthday" id="birthday" value="{$_POST['birthday']}" />
						</fieldset>
						<fieldset>
							<label for="bio">Bio</label>
							<textarea id="bio" name="bio">{$_POST['bio']}</textarea>
							<small>Describe yourself in <span class="count">255</span> characters or less</small>
						</fieldset>
						
						<fieldset class="terms">
							<label>Boring Legal Stuff *</label>
							<p><input type="checkbox" name="terms" id="terms" />I have read and agree to the <a href="/terms" title="WDD Social Terms of Service">Terms of Service</a>.</p>
						</fieldset>
						<p class="helper-link"><a href="/signin" title="Already a WDD Social member?">Already a member?</a></p>
						<input type="submit" name="submit" value="Sign Up" />
					</form>
HTML;
		return $html;
	}
}