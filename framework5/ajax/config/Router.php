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
			case 'available':
				return 'ajax.request.Ajax\Available';
				
			case 'get':
				return 'ajax.request.Ajax\Get';
				
			case 'more':
				return 'ajax.request.Ajax\LoadMore';
				
			case 'content':
				return 'ajax.request.Ajax\Content';
				
			case 'flag':
				return 'ajax.request.Ajax\Flag';
			
			default:
				return 'ajax.request.Ajax\InvalidRequest';
		}
	}
}