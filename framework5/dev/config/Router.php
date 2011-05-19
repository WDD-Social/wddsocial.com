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
			
			case 'exec':
				return 'dev.page.Framework5\Dev\ExecPage';
			
			case 'requests':
				return 'dev.page.Framework5\Dev\RequestsPage';
			
			case 'request':
				return 'dev.page.Framework5\Dev\RequestInfoPage';
			
			case 'bugs':
				return 'dev.page.Framework5\Dev\BugTrackerPage';
			
			case 'phpinfo':
				return 'dev.page.Framework5\Dev\PHPInfoPage';
			
			default:
				return 'dev.page.Framework5\Dev\Http404';
		}
	}
}