<?php

/*
* WDD Social: Language Pack for 
*/

class ForgotPasswordPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'Forgot Password';
			
			case 'success-message':
				return 'A link has been sent to your email address to reset your password.';
			
			case 'intro-message':
				return 'Have you forgotten your password? Enter one of the email addresses you entered during signup and we&rsquo;ll email you a link to reset your password.';
		}
	}
}