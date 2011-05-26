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
			
			
			# user area
			case 'search':
				return 'Search';
			
			case 'search_placeholder':
				return 'Search...';
			
			
			# user signed in
			case 'user-profile-title':
				return 'View My Profile';
			
			case 'create':
				return 'Create';
			
			case 'create-title':
				return 'Share a Project, Article, Event or Job';
			
			case 'messages':
				return 'Messages';
			
			case 'messages-title':
				return 'View My Messages';
			
			case 'account':
				return 'Account';
			
			case 'account-title':
				return 'View and Edit my Account Information';
			
			case 'signout':
				return 'Sign Out';
			
			case 'signout-title':
				return 'Sign Out of WDD Social';
			
			
			# user not signed in
			case 'signup':
				return 'Sign Up';
			
			case 'signup-title':
				return 'Sign Up for WDD Social';
			
			case 'signin':
				return 'Sign In';
			
			case 'signin-title':
				return 'Sign In for WDD Social';
			
			case 'hiring':
				return 'Hiring?';
			
			case 'post-a-job':
				return 'Post a Job';
			
			case 'post-a-job-title':
				return 'WDD Social | Post a Job';
			
			
			# footer
			case 'issue':
				return 'Report an Issue';
			
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
			
			
			# footer titles
			case 'issue-title':
				return 'Issue Tracker';
			
			case 'developer-title':
				return 'Developer Resources';
			
			case 'about-title':
				return 'About Us';
			
			case 'contact-title':
				return 'Contact Us';
			
			case 'terms-title':
				return 'Terms of Service';
			
			case 'privacy-title':
				return 'Privacy Policy';
			
			# error
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}