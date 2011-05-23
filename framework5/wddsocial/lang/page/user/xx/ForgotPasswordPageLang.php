<?php

/*
* WDD Social: Language Pack for ForgotPasswordPage
* Language: xx
*/

class ForgotPasswordPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'XX'; # Forgot Password
			
			case 'success-message':
				return 'XX'; # A link has been sent to your email address to reset your password.
			
			case 'intro-message':
				return 'XX'; # Have you forgotten your password? Enter one of the email addresses you entered during signup and we&rsquo;ll email you a link to reset your password.
		}
	}
}