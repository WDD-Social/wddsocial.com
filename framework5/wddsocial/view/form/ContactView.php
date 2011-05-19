<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ContactView implements \Framework5\IView {		
	
	public function render($options = null) {
		if (UserSession::is_authorized()) {
			
			$db = instance(':db');
			$sql = instance(':sel-sql');
			
			$query = $db->prepare($sql->getUserEmailByID);
			$query->execute(array('id' => UserSession::userid()));
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$result = $query->fetch();
			
			$name = UserSession::user_name();
			$email = $result->email;
		}
		
		if (isset($_POST['name']) and $_POST['name'] != '') {
			$name = $_POST['name'];
		}
		
		if (isset($_POST['email']) and $_POST['email'] != '') {
			$email = $_POST['email'];
		}
		
		if (isset($name) and $name != '') {
			$nameAutofocus = '';
			if (isset($email) and $email != '') {
				$emailAutofocus = '';
				if (isset($_POST['message']) and $_POST['message'] != '') {
					$messageAutofocus = '';
				}
				else {
					$messageAutofocus = ' autofocus';
				}
			}
			else {
				$emailAutofocus = ' autofocus';
			}
		}
		else {
			$nameAutofocus = ' autofocus';
		}
		
		
		
		
		
		if ($options['success']) {
			$html.= <<<HTML

						<p class="success"><strong>{$options['success']}</strong></p>
HTML;
		}
		
		
		else {
			$html = <<<HTML

					<h1 class="mega">Hey, what&rsquo;s up? We&rsquo;d love to talk!</h1>
					<form action="/contact" method="post">
HTML;
		
			# if error
			if ($options['error']) {
				$html.= <<<HTML

						<p class="error"><strong>{$options['error']}</strong></p>
HTML;
			}
		
			$html.= <<<HTML
						<fieldset>
							<label for="name">Name</label>
							<input type="text" name="name" id="name" value="{$name}"$nameAutofocus />
						</fieldset>
						<fieldset>
							<label for="email">Email</label>
							<input type="email" name="email" id="email" value="{$email}"$emailAutofocus />
						</fieldset>
						<fieldset>
							<label for="message">Message</label>
							<textarea name="message" id="message" placeholder="So, tell us what&rsquo;s going on!"$messageAutofocus>{$_POST['message']}</textarea>
						</fieldset>
						<input type="submit" name="submit" value="Send" />
					</form>
HTML;
		}
		
		return $html;
	}
}