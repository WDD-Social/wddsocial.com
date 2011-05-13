<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class AccountView implements \Framework5\IView {
	public function render($options = null) {
		$user = $options['user'];
		if ($options['error'] != '') {
			# Map post data to user object
			$user->firstName = $_POST['first-name'];
			$user->lastName = $_POST['last-name'];
			$user->email = $_POST['email'];
			$user->fullsailEmail = $_POST['full-sail-email'];
			$user->vanityURL = $_POST['vanityURL'];
			$user->hometown = $_POST['hometown'];
			$user->birthday = $_POST['birthday'];
			$user->bio = $_POST['bio'];
			$user->typeID = $_POST['user-type'];
			$user->contact['website'] = $_POST['website'];
			$user->contact['twitter'] = $_POST['twitter'];
			$user->contact['facebook'] = $_POST['facebook'];
			$user->contact['github'] = $_POST['github'];
			$user->contact['dribbble'] = $_POST['dribbble'];
			$user->contact['forrst'] = $_POST['forrst'];
			$user->extra['startDate'] = $_POST['start-date'];
			$user->extra['location'] = $_POST['degree-location'];
			$user->extra['graduationDate'] = $_POST['graduation-date'];
			$user->extra['employerTitle'] = $_POST['employer'];
			$user->extra['employerLink'] = $_POST['employer-link'];
		}
		$html .= <<<HTML

					<form action="/account" method="post" enctype="multipart/form-data">
						<h1>Basics</h1>
						<h2>* Notice *</h2>
						<p><strong>Basics</strong>, <strong>contact info</strong>, <strong>password</strong>, and <strong>other simple fields</strong> are updatable.</p>
						<p>Actions such as <strong>uploading a new avatar</strong>, <strong>changing user-type-specific data</strong> (start date, graduation date, courses you teach, etc.), and more are coming shortly. Please check back soon for those actions.</p>
						<p class="error"><strong>{$options['error']}</strong></p>
						<fieldset>
							<label for="first-name">First Name</label>
							<input type="text" name="first-name" id="first-name" value="{$user->firstName}" />
						</fieldset>
						<fieldset>
							<label for="last-name">Last Name</label>
							<input type="text" name="last-name" id="last-name" value="{$user->lastName}" />
						</fieldset>
						<fieldset>
							<label for="email">Email</label>
							<input type="email" name="email" id="email" value="{$user->email}" />
						</fieldset>
						<fieldset>
							<label for="full-sail-email">Full Sail Email</label>
							<input type="email" name="full-sail-email" id="full-sail-email" value="{$user->fullsailEmail}" />
							<small>Used for account verification</small>
						</fieldset>
						<fieldset>
							<label for="vanityURL">Custom Vanity URL</label>
							<input type="text" name="vanityURL" id="vanityURL" value="{$user->vanityURL}" />
							<small>wddsocial.com/user/<strong>{$user->vanityURL}</strong></small>
						</fieldset>
						<input type="submit" name="submit" value="Save" />
						
						<h1>Change Password</h1>
						<fieldset>
							<label for="old-password">Old Password</label>
							<input type="password" name="old-password" id="old-password" />
						</fieldset>
						<fieldset>
							<label for="new-password">New Password</label>
							<input type="password" name="new-password" id="new-password" />
							<small>6 or more characters, please</small>
						</fieldset>
						<input type="submit" name="submit" value="Save" />
						
						<h1>More Information</h1>
HTML;
		$html .= <<<HTML

						<fieldset>
							<label for="avatar">New Avatar</label>
							<input type="file" name="avatar" id="avatar" />
						</fieldset>
						<fieldset>
							<label for="hometown">Hometown</label>
							<input type="text" name="hometown" id="hometown" value="{$user->hometown}" />
						</fieldset>
						<fieldset>
							<label for="birthday">Birthday</label>
							<input type="text" name="birthday" id="birthday" value="{$user->birthday}" />
						</fieldset>
						<fieldset>
							<label for="bio">Bio</label>
							<textarea id="bio" name="bio">{$user->bio}</textarea>
							<small>Describe yourself in <span class="count">255</span> characters or less</small>
						</fieldset>
HTML;
		$html .= render('wddsocial.view.form.pieces.WDDSocial\UserTypeSelector', array('typeID' => $user->typeID, 'required' => false));
		$html .= render('wddsocial.view.form.pieces.WDDSocial\UserDetailInputs', $user->extra);
		
		$html .= <<<HTML

						<input type="submit" name="submit" value="Save" />

						<h1>Contact</h1>
						<fieldset>
							<label for="website">Website</label>
							<input type="text" name="website" id="website" placeholder="example.com" value="{$user->contact['website']}" />
						</fieldset>
						<fieldset>
							<label for="twitter">Twitter</label>
							<input type="text" name="twitter" id="twitter" placeholder="username" value="{$user->contact['twitter']}" />
							<small>@username</small>
						</fieldset>
						<fieldset>
							<label for="facebook">Facebook</label>
							<input type="text" name="facebook" id="facebook" placeholder="username" value="{$user->contact['facebook']}" />
							<small>facebook.com/username</small>
						</fieldset>
						<fieldset>
							<label for="github">Github</label>
							<input type="text" name="github" id="github" placeholder="username" value="{$user->contact['github']}" />
							<small>github.com/username</small>
						</fieldset>
						<fieldset>
							<label for="dribbble">Dribbble</label>
							<input type="text" name="dribbble" id="dribbble" placeholder="username" value="{$user->contact['dribbble']}" />
							<small>dribbble.com/username</small>
						</fieldset>
						<fieldset>
							<label for="forrst">Forrst</label>
							<input type="text" name="forrst" id="forrst" placeholder="username" value="{$user->contact['forrst']}" />
							<small>forrst.com/people/username</small>
						</fieldset>
						
						<p class="helper-link"><a href="/remove/user/{$user->vanityURL}" title="Delete your WDD Social Account">Delete Account</a></p>
						<input type="submit" name="submit" value="Save" />
					</form>
HTML;
		return $html;
	}
}