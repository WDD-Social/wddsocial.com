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
		$exampleBirthday = date('F j, Y',time() - (25*365.25*24*60*60));
		$html .= <<<HTML

					<h1 class="mega form">Edit your account and profile information</h1>
					<h2 class="form">* Required</h2>
					<form action="/account" id="account" method="post" enctype="multipart/form-data">
						<h1>Basics</h1>
						<p class="error"><strong>{$options['error']}</strong></p>
						<fieldset>
							<label for="first-name">First Name *</label>
							<input type="text" name="first-name" id="first-name" value="{$user->firstName}" />
						</fieldset>
						<fieldset>
							<label for="last-name">Last Name *</label>
							<input type="text" name="last-name" id="last-name" value="{$user->lastName}" />
						</fieldset>
						<fieldset>
							<label for="email">Email *</label>
							<input type="email" name="email" id="email" value="{$user->email}" />
						</fieldset>
						<fieldset>
							<label for="full-sail-email">Full Sail Email *</label>
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
							<input type="password" name="new-password" id="new-password" class="check-length" />
							<small>6 or more characters, please</small>
						</fieldset>
						<input type="submit" name="submit" value="Save" />
						
						<h1>More Information</h1>
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
							<small>Example: <strong>$exampleBirthday</strong></small>
						</fieldset>
						<fieldset>
							<label for="bio">Bio</label>
							<textarea id="bio" name="bio" maxlength="255">{$user->bio}</textarea>
							<small>Describe yourself in <span class="count">255</span> characters or less</small>
						</fieldset>
HTML;
		$html .= render('wddsocial.view.form.pieces.WDDSocial\UserTypeSelector', array('typeID' => $user->typeID, 'id' => ' id="user-type"'));
		$html .= <<<HTML

						<div id="user-type-details">
HTML;
		switch ($user->typeID) {
			case 1:
				$html .= render('wddsocial.view.form.pieces.WDDSocial\StudentDetailInputs', $user->extra);
				break;
			case 2:
				$html .= render('wddsocial.view.form.pieces.WDDSocial\TeacherDetailInputs', $user->extra);
				break;
			case 3:
				$html .= render('wddsocial.view.form.pieces.WDDSocial\AlumDetailInputs', $user->extra);
				break;
		}
		
		$html .= <<<HTML

						</div><!-- END user-type-details -->
						<input type="submit" name="submit" value="Save" />
HTML;
		$html .= render('wddsocial.view.form.pieces.WDDSocial\LikesDislikesInputs', array('likes' => $user->extra['likes'], 'dislikes' => $user->extra['dislikes']));
		$html .= <<<HTML

						<h1 id="contact">Contact</h1>
						<p>Just provide your usernames for the social networks that you wish to show on your profile, we&rsquo;ll do the rest of the work!</p>
						<fieldset>
							<label for="website">Website</label>
							<input type="text" name="website" id="website" placeholder="example.com" value="{$user->contact['website']}" />
							<small><strong>example.com</strong></small>
						</fieldset>
						<fieldset>
							<label for="twitter">Twitter</label>
							<input type="text" name="twitter" id="twitter" placeholder="username" value="{$user->contact['twitter']}" />
							<small>twitter.com/<strong>username</strong></small>
						</fieldset>
						<fieldset>
							<label for="facebook">Facebook</label>
							<input type="text" name="facebook" id="facebook" placeholder="username" value="{$user->contact['facebook']}" />
							<small>facebook.com/<strong>username</strong></small>
						</fieldset>
						<fieldset>
							<label for="github">Github</label>
							<input type="text" name="github" id="github" placeholder="username" value="{$user->contact['github']}" />
							<small>github.com/<strong>username</strong></small>
						</fieldset>
						<fieldset>
							<label for="dribbble">Dribbble</label>
							<input type="text" name="dribbble" id="dribbble" placeholder="username" value="{$user->contact['dribbble']}" />
							<small>dribbble.com/<strong>username</strong></small>
						</fieldset>
						<fieldset>
							<label for="forrst">Forrst</label>
							<input type="text" name="forrst" id="forrst" placeholder="username" value="{$user->contact['forrst']}" />
							<small>forrst.com/people/<strong>username</strong></small>
						</fieldset>
						
						<p class="helper-link"><a href="/delete/user/{$user->vanityURL}" title="Delete your WDD Social Account">Delete Account</a></p>
						<input type="submit" name="submit" value="Save" />
					</form>
HTML;
		return $html;
	}
}