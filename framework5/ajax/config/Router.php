<?php

namespace Ajax;

/*
* Application Request Router
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Router implements \Framework5\IRouter {
	
	# route uri path to a controller
	public static function resolve($request) {
		
		switch ($request) {
			case 'getLatest':
				return 'ajax.request.Ajax\GetLatest';
			
			default:
				return 'ajax.request.Ajax\InvalidRequest';
		}
	}
}