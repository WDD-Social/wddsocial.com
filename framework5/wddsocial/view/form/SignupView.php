<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class SignUpView implements \Framework5\IView {		
	
	public static function render($options = null) {
		$root = \Framework5\Request::root_path();
		return <<<HTML

					<form action="{$root}signup" method="post" enctype="multipart/form-data">
					<h1>Basic</h1>
					<p class="error"><strong>{$options['error']}</strong></p>
					<fieldset>
						<label for="first-name">First Name *</label>
						<input type="text" name="first-name" id="first-name" />
					</fieldset>
					<fieldset>
						<label for="last-name">Last Name *</label>
						<input type="text" name="last-name" id="last-name" />
					</fieldset>
					<fieldset>
						<label for="email">Email *</label>
						<input type="email" name="email" id="email" />
					</fieldset>
					<fieldset>
						<label for="full-sail-email">Full Sail Email *</label>
						<input type="email" name="full-sail-email" id="full-sail-email" />
						<small>Used for account verification</small>
					</fieldset>
					<fieldset>
						<label for="password">Password *</label>
						<input type="password" name="password" id="password" />
						<small>6 or more characters</small>
					</fieldset>
					
					<h1>Background</h1>
					<fieldset class="radio">
						<label>I am a...*</label>
						<div>
							<input type="radio" id="student" name="user-type" value="student" checked />
							<label for="student">Student</label>
							
							<input type="radio" id="teacher" name="user-type" value="teacher" />
							<label for="teacher">Teacher</label>
							
							<input type="radio" id="alum" name="user-type" value="alum" />
							<label for="alum">Alum</label>
						</div>
					</fieldset>
					<fieldset>
						<label for="avatar">Avatar</label>
						<input type="file" name="avatar" id="avatar" />
					</fieldset>
					<fieldset>
						<label for="hometown">Hometown</label>
						<input type="text" name="hometown" id="hometown" />
					</fieldset>
					<fieldset>
						<label for="birthday">Birthday</label>
						<input type="text" name="birthday" id="birthday" />
					</fieldset>
					<fieldset>
						<label for="bio">Bio</label>
						<textarea id="bio"></textarea>
						<small><span class="count">255</span> characters left</small>
					</fieldset>
					
					<fieldset class="terms">
						<label>Boring Legal Stuff *</label>
						<p><input type="checkbox" name="terms" id="terms" />I have read and agree to the <a href="{$root}terms" title="WDD Social Terms of Service">Terms of Service</a>.</p>
					</fieldset>
					<p class="helper-link"><a href="{$root}signin" title="Already a WDD Social member?">Already a member?</a></p>
					<input type="submit" name="submit" value="Sign Up" />
				</form>
HTML;
	}
}