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
			
			
			case 'user':
				return 'wddsocial.page.global.WDDSocial\UserPage';
			
			
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
				return 'wddsocial.page.global.global.WDDSocial\PrivacyPage';
			
			# 404
			default:
				return 'wddsocial.page.global.error.WDDSocial\Http404';
		}
	}
}