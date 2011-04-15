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
				return 'dev.page.IndexPage';
			
			case 'requests' or 'request':
				return 'dev.page.RequestInfoPage';
			
			default:
				return 'dev.page.Http404';
		}
	}
}