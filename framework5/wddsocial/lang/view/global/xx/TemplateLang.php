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
			case 'user-profile-title':
				return 'XX'; # View My Profile
			
			case 'create':
				return 'XX'; # Create
			
			case 'create-title':
				return 'XX'; # Share a Project, Article, Event or Job
			
			case 'messages':
				return 'XX'; # Messages
			
			case 'messages-title':
				return 'XX'; # View My Messages
				
			case 'account':
				return 'XX'; # Account
			
			case 'account-title':
				return 'XX'; # View and Edit my Account Information
			
			case 'signout':
				return 'XX'; # Sign Out
			
			case 'signout-title':
				return 'XX'; # Sign Out of WDD Social
			
			
			# user not signed in
			case 'signup':
				return 'XX'; # Sign Up
			
			case 'signup-title':
				return 'XX'; # Sign Up for WDD Social
			
			case 'signin':
				return 'XX'; # Sign In
			
			case 'signin-title':
				return 'XX'; # Sign In for WDD Social
			
			case 'hiring':
				return 'XX'; # Hiring?
			
			case 'post-a-job':
				return 'XX'; # Post a Job
			
			case 'post-a-job-title':
				return 'XX'; # WDD Social | Post a Job
			
			
			# footer
			case 'issue':
				return 'XX'; # Report an Issue
			
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
			case 'issue-title':
				return 'XX'; # Issue Tracker
			
			case 'developer-title':
				return 'XX'; # Developer Resources
			
			case 'about-title':
				return 'XX'; # About Us
			
			case 'contact-title':
				return 'XX'; # Contact Us
			
			case 'terms-title':
				return 'XX'; # Terms of Service
			
			case 'privacy-title':
				return 'XX'; # Privacy Policy
			
			# error
			default:
				throw new Exception("Language pack content '$id' not found");
		}
	}
}