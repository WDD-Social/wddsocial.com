<?php

namespace Framework5;

/*
* WDDSocialApplication controller
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

final class DeveloperApplication extends ApplicationBase implements IApplication {
	
	/**
	* Execute a request. Called by the front controller.
	*/
	
	public function execute() {
		
		# disable logging
		Settings::$log_debug = false;
		Settings::$log_execution = false;
		Settings::$log_exception = false;
		
		# resolve request to a page controller
		import('dev.config.Framework5\Dev\Router');
		$package = Dev\Router::resolve(Request::segment(1));
		
		# execute the controller
		execute($package);
		return true;
	}
}