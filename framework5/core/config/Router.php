<?php

namespace Framework5;

/*
* Framework5 Front Controller Router
* 	- routes a uri array request to an application controller.
* 	- one or more Application controllers may be defined.
* 	- the resolve method should return a package name. ex: 'core.controller.Controller'
* 	- the router handles the first value in the passed request array
* 	- all controllers defined must implement the IApplication interface
* 
*/

class Router implements IRouter {
	
	public static function resolve($request) {
		switch ($request) {
			default:
				return 'wddsocial.app.Framework5\WDDSocialApplication';
		}
	}
}