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
		
		# restrict access
		$this->restrict_access();
		
		# resolve request to a page controller
		import('dev.config.Framework5\Dev\Router');
		$package = Dev\Router::resolve(Request::segment(1));
		
		# execute the controller
		execute($package);
		return true;
	}
	
	
	private function restrict_access() {
		
		# include WDD Social UserSession
		import('wddsocial.controller.WDDSocial\UserSession');
		import('wddsocial.controller.WDDSocial\Exception');
		
		\WDDSocial\UserSession::init();
		\WDDSocial\UserSession::protect();
		$userid = \WDDSocial\UserSession::userid();
		
		# if not tyler or anthony, gtfo
		if ($userid != '1' and $userid != '2') redirect('/');
	}
}