<?php

namespace Framework5;

/*
* DeveloperApplication controller
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

final class DeveloperApplication extends ApplicationBase implements IApplication {
	
	/**
	* Execute a request. Called by the front controller.
	*/
	
	public function execute() {
		
		$this->init();
		
		# import global app dependencies
		$this->global_import();
		
		# restrict access
		$this->restrict_access();
		
		# resolve request to a page controller
		import('dev.config.Framework5\Dev\Router');
		$package = Dev\Router::resolve(Request::segment(1));
		
		# execute the controller
		execute($package);
		return true;
	}
	
	
	
	/**
	* Initialize the application
	*/
	
	private function init() {
		
		# disable logging
		Settings::$log_debug = false;
		Settings::$log_execution = false;
		Settings::$log_exception = false;
		
		# import application settings
		import('dev.config.Framework5\Dev\AppSettings');
		
		# load package aliases
		PackageManager::define_alias_array(\Framework5\Dev\AppSettings::$package_aliases);
		
	}
	
	
	
	/**
	* Import global files
	*/
	
	private function global_import() {
		import('dev.controller.Framework5\Dev\Exception');
		import('dev.controller.Framework5\Dev\Formatter');
	}
	
	
	
	/**
	* Restrict access to Tyler or Anthony
	*/
	
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