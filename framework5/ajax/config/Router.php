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
			case 'latest':
				return 'ajax.request.Ajax\GetLatest';
			case 'available':
				return 'ajax.request.Ajax\Available';
			
			
			case 'test':
				return 'ajax.request.Ajax\Test';
			default:
				return 'ajax.request.Ajax\InvalidRequest';
		}
	}
}