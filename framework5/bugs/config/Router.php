<?php

namespace Framework5\Bugs;

/*
* Application Request Router
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Router implements \Framework5\IRouter {
	
	# route uri path to a controller
	public static function resolve($request) {
		
		switch ($request) {
			case 'report':
				return 'bugs.page.Framework5\Bugs\ReportPage';
			
			default:
				return 'bugs.page.Framework5\Bugs\Http404';
		}
	}
}