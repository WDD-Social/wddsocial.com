<?php

/*
* WDD Social: Template Language Pack
*/

class TemplateLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			# navigation elements
			case 'people':
				return 'People';
			case 'projects':
				return 'Projects';
			case 'articles':
				return 'Articles';
			case 'courses':
				return 'Courses';
			case 'events':
				return 'Events';
			case 'jobs':
				return 'Jobs';
				
			# header
			case 'register':
				return 'Register';
			case 'signin':
				return 'Sign In';
			case 'messages':
				return 'Messages';
			case 'account':
				return 'Account';
			case 'signout':
				return 'Sign Out';
			case 'search':
				return 'Search';
				
			# footer
			case 'copyright':
				return '&copy; 2011 WDD Social';
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
				
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}