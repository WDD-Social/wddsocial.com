<?php

/*
* WDD Social: Template Language Pack
*/

class TemplateLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			# navigation elements
			case 'people':
				return 'Membres';
			case 'projects':
				return 'Projets';
			case 'articles':
				return 'Articles';
			case 'courses':
				return 'Classes';
			case 'events':
				return '&Eacute;v&eacute;nements';
			case 'jobs':
				return 'Jobs';
				
			# user area
			case 'search':
				return 'Recherche';
			case 'search_placeholder':
				return 'Recherche...';
			
			# 	user signed in
			case 'user_profile_title':
				return 'View My Profile';
			case 'messages':
				return 'Messages';
			case 'messages_title':
				return 'Messages';
			case 'account':
				return 'Compte';
			case 'account_title':
				return 'View and Edit my Account Information';
			case 'signout':
				return 'Deconnexion';
			case 'signout_title':
				return 'Sign Out of WDD Social';
			
			# 	user not signed in
			case 'signup':
				return 'Sign Up';
			case 'signup_title':
				return 'Sign Up for WDD Social';
			case 'signin':
				return 'Sign In';
			case 'signin_title':
				return 'Sign In for WDD Social';
				
			# footer
			case 'developer':
				return 'Developer';
			case 'about':
				return 'About';
			case 'contact':
				return 'Contact';
			case 'terms':
				return 'Terms';
			case 'privacy':
				return 'Privacy';
			
			case 'developer_desc':
				return 'Developer Resources';
			case 'about_desc':
				return 'About Us';
			case 'contact_desc':
				return 'Contact Us';
			case 'terms_desc':
				return 'Terms of Service';
			case 'privacy_desc':
				return 'Privacy Policy';
				
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}