<?php

/*
* WDD Social: Template Language Pack
*/

class TemplateLang implements \Framework5\ILanguagePack {
	
	public static function content($id, $var) {
		switch ($id) {
			
			# navigation elements
			case 'people':
				return 'XX'; # People
				
			case 'projects':
				return 'XX'; # Projects
				
			case 'articles':
				return 'XX'; # Articles
				
			case 'courses':
				return 'XX'; # Courses
				
			case 'events':
				return 'XX'; # Events
				
			case 'jobs':
				return 'XX'; # Jobs
			
			
			# user area
			case 'search':
				return 'XX'; # Search
			
			case 'search_placeholder':
				return 'XX'; # Search...
			
			# user signed in
			case 'user_profile_title':
				return 'XX'; # View My Profile
			
			case 'messages':
				return 'XX'; # Messages
			
			case 'messages_title':
				return 'XX'; # View My Messages
				
			case 'account':
				return 'XX'; # Account
			
			case 'account_title':
				return 'XX'; # View and Edit my Account Information
			
			case 'signout':
				return 'XX'; # Sign Out
			
			case 'signout_title':
				return 'XX'; # Sign Out of WDD Social
			
			# user not signed in
			case 'signup':
				return 'XX'; # Sign Up
			case 'signup_title':
				return 'XX'; # Sign Up for WDD Social
			case 'signin':
				return 'XX'; # Sign In
			case 'signin_title':
				return 'XX'; # Sign In for WDD Social
			
			# footer
			case 'developer':
				return 'XX'; # Developer
			
			case 'about':
				return 'XX'; # About
			
			case 'contact':
				return 'XX'; # Contact
			
			case 'terms':
				return 'XX'; # Terms
			
			case 'privacy':
				return 'XX'; # Privacy
			
			# footer titles
			case 'developer_desc':
				return 'XX'; # Developer Resources
				
			case 'about_desc':
				return 'XX'; # About Us
				
			case 'contact_desc':
				return 'XX'; # Contact Us
				
			case 'terms_desc':
				return 'XX'; # Terms of Service
				
			case 'privacy_desc':
				return 'XX'; # Privacy Policy
			
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}