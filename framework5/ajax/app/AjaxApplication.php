<?php

namespace Framework5;

/*
* AjaxApplication controller
* 	WDD Social ajax gateway
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

final class AjaxApplication extends ApplicationBase implements IApplication {
	
	/**
	* Execute a request. Called by the front controller.
	*/
	
	public function execute() {
		
		# init the application
		$this->init();
		
		# import packages in every request
		$this->global_import();
		
		# check user session
		import('wddsocial.controller.WDDSocial\UserSession');
		\WDDSocial\UserSession::init();
		
		# resolve request to a page controller
		import('ajax.config.Ajax\Router');
		$package = \Ajax\Router::resolve(Request::segment(1));
		
		# execute the controller
		execute($package);
		return true;
	}
	
	
	
	/**
	* Initialize the application
	*/
	
	private function init() {
		# import application settings
		import('ajax.config.Ajax\AjaxSettings');
		
		# load package aliases
		PackageManager::define_alias_array(\Ajax\AjaxSettings::$package_aliases);
	}
	
	
	
	/**
	*  import application global dependencies
	*/
	
	private function global_import() {
		import('ajax.controller.Ajax\Exception');
		import('wddsocial.controller.Framework5\Exception');
		
		import('wddsocial.controller.WDDSocial\UserValidator');
		import('wddsocial.helper.WDDSocial\NaturalLanguage');
	}
}