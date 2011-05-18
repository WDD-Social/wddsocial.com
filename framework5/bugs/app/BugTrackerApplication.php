<?php

namespace Framework5;

/*
* BugTrackerApplication controller
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

final class BugTrackerApplication extends ApplicationBase implements IApplication {
	
	/**
	* Execute a request. Called by the front controller.
	*/
	
	public function execute() {
		
		$this->init();
		
		# check user session
		import('wddsocial.controller.WDDSocial\UserSession');
		\WDDSocial\UserSession::init();
		
		$this->localize();
		
		# resolve request to a page controller
		import('bugs.config.Framework5\Bugs\Router');
		$package = Bugs\Router::resolve(Request::segment(1));
		
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
		import('bugs.config.Framework5\Bugs\AppSettings');
		
		# load package aliases
		PackageManager::define_alias_array(\Framework5\Bugs\AppSettings::$package_aliases);
		
	}
	
	
	
	private function localize() {
		import('core.module.i18n.Framework5\Lang');
		if (\WDDSocial\UserSession::is_authorized()) {
			Lang::language(\WDDSocial\UserSession::user_lang());
		}
	}
}