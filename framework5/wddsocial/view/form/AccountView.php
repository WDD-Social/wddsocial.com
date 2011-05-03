<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class AccountView implements \Framework5\IView {		
	
	public static function render($options = null) {
		$root = \Framework5\Request::root_path();
		return <<<HTML

					<form action="{$root}account" method="post" enctype="multipart/form-data">
					<h1>Basic</h1>
					<p class="error"><strong>{$options['error']}</strong></p>
					<fieldset>
						<label for="first-name">First Name</label>
						<input type="text" name="first-name" id="first-name" />
					</fieldset>
					<fieldset>
						<label for="last-name">Last Name</label>
						<input type="text" name="last-name" id="last-name" />
					</fieldset>
					<fieldset>
						<label for="email">Email</label>
						<input type="email" name="email" id="email" />
					</fieldset>
					<fieldset>
						<label for="full-sail-email">Full Sail Email</label>
						<input type="email" name="full-sail-email" id="full-sail-email" />
						<small>Used for account verification</small>
					</fieldset>
					
					<h1>Change Password</h1>
					<fieldset>
						<label for="old-password">Old Password</label>
						<input type="password" name="old-password" id="old-password" />
					</fieldset>
					<fieldset>
						<label for="new-password">New Password</label>
						<input type="password" name="new-password" id="new-password" />
						<small>6 or more characters</small>
					</fieldset>
					
					<h1>Background</h1>
					<fieldset class="radio">
						<label>I am a...</label>
						<div>
							<input type="radio" id="student" name="user-type" value="student" />
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
					
					<p class="helper-link"><a href="{$root}remove/account" title="Delete your WDD Social Account">Delete Account</a></p>
					<input type="hidden" name="process" value="account" />
					<input type="submit" value="Save" />
				</form>
HTML;
	}
}