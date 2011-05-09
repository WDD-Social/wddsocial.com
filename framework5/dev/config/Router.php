<?php

namespace Framework5\Dev;

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
				return 'dev.page.Framework5\Dev\IndexPage';
			
			case 'requests':
				return 'dev.page.Framework5\Dev\RequestsPage';
			
			case 'request':
				return 'dev.page.Framework5\Dev\RequestInfoPage';
			
			default:
				return 'dev.page.Framework5\Dev\Http404';
		}
	}
}