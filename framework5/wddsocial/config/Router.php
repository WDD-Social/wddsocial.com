<?php

namespace WDDSocial;

/*
* Application Request Router
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Router implements \Framework5\IRouter {
	
	# route uri path to a controller
	public static function resolve($request) {
		
		switch ($request) {
			
			# site index
			case '':
				return 'wddsocial.page.global.WDDSocial\IndexPage';
			
			case 'home':
				return 'wddsocial.page.global.WDDSocial\IndexPage';
			
			# main navigation
			case 'people':
				return 'wddsocial.page.global.WDDSocial\PeoplePage';
			
			case 'projects':
				return 'wddsocial.page.global.WDDSocial\ProjectsPage';
			
			case 'articles':
				return 'wddsocial.page.global.WDDSocial\ArticlesPage';
			
			case 'courses':
				return 'wddsocial.page.global.WDDSocial\CoursesPage';
			
			case 'events':
				return 'wddsocial.page.global.WDDSocial\EventsPage';
			
			case 'jobs':
				return 'wddsocial.page.global.WDDSocial\JobsPage';
			
			case 'search':
				return 'wddsocial.page.global.WDDSocial\SearchPage';
			
			
			# global user actions
			case 'signup':
				return 'wddsocial.page.user.WDDSocial\SignupPage';
			
			case 'signin':
				return 'wddsocial.page.user.WDDSocial\SigninPage';
			
			case 'signout':
				return 'wddsocial.page.user.WDDSocial\SignoutPage';
			
			case 'account':
				return 'wddsocial.page.user.WDDSocial\AccountPage';
			
			case 'messages':
				return 'wddsocial.page.user.WDDSocial\MessagesPage';
			
			case 'verify':
				return 'wddsocial.page.user.WDDSocial\VerifyPage';
			
			
			# footer navigation
			case 'developer':
				return 'wddsocial.page.global.WDDSocial\DeveloperPage';
			
			case 'about':
				return 'wddsocial.page.global.WDDSocial\AboutPage';
			
			case 'contact':
				return 'wddsocial.page.global.WDDSocial\ContactPage';
			
			case 'terms':
				return 'wddsocial.page.global.WDDSocial\TermsPage';
				
			case 'privacy':
				return 'wddsocial.page.global.WDDSocial\PrivacyPage';
			
			
			
			case 'user':
				return 'wddsocial.page.global.WDDSocial\UserPage';
			
			case 'project':
				return 'wddsocial.page.global.WDDSocial\ProjectPage';
			
			case 'article':
				return 'wddsocial.page.global.WDDSocial\ArticlePage';
			
			case 'course':
				return 'wddsocial.page.global.WDDSocial\CoursePage';
			
			case 'event':
				return 'wddsocial.page.global.WDDSocial\EventPage';
			
			case 'job':
				return 'wddsocial.page.global.WDDSocial\JobPage';
			
			case 'create':
				return 'wddsocial.page.form.WDDSocial\CreatePage';
			
			
			
			# 404
			default:
				return 'wddsocial.page.error.WDDSocial\Http404';
		}
	}
}