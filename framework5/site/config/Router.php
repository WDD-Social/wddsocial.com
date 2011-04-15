<?php

/*
* Application Request Router
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Router implements Framework5\IRouter {
	
	# route uri path to a controller
	public static function resolve($request) {
		
		switch ($request[1]) {
			case '':
				return 'site.page.IndexPage';
				
			case 'user':
				return 'site.page.ProfilePage';
			
			case 'about':
				return 'site.page.AboutPage';
			
			case 'lang':
				return 'site.page.LangPage';
			
			default:
				return 'site.page.Http404Page';
		}
	}
}