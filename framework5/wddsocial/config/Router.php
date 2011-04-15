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
			case '':
				return 'wddsocial.page.WDDSocial\IndexPage';
				
			case 'user':
				return 'wddsocial.page.WDDSocial\ProfilePage';
			
			case 'about':
				return 'wddsocial.page.WDDSocial\AboutPage';
			
			default:
				return 'wddsocial.page.WDDSocial\Http404';
		}
	}
}