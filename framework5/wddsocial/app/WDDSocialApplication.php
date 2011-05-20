<?php

namespace Framework5;

/*
* WDDSocialApplication controller
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

final class WDDSocialApplication extends ApplicationBase implements IApplication {
	
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
		
		# enable localization module
		$this->localize();
		
		# resolve request to a page controller
		import('wddsocial.config.WDDSocial\Router');
		$package = \WDDSocial\Router::resolve(Request::segment(0));
		
		# execute the controller
		execute($package);
		
		$_SESSION['last_request'] = Request::uri();
		
		return true;
	}
	
	
	
	/**
	* Initialize the application
	*/
	
	private function init() {
		
		# import application settings
		import('wddsocial.config.WDDSocial\AppSettings');
		
		# load package aliases
		PackageManager::define_alias_array(\WDDSocial\AppSettings::$package_aliases);
		
	}
	
	
	
	/**
	*  import application global dependencies
	*/
	
	private function global_import() {
		
		import('wddsocial.controller.WDDSocial\Exception');
		import('wddsocial.controller.WDDSocial\UserValidator');
		import('wddsocial.helper.WDDSocial\NaturalLanguage');
		import('wddsocial.helper.WDDSocial\StringCleaner');
		import('wddsocial.sql.WDDSocial\SelectorSQL');
		import('wddsocial.controller.WDDSocial\Paginator');
		
	}
	
	
	
	private function localize() {
		import('core.module.i18n.Framework5\Lang');
		if (\WDDSocial\UserSession::is_authorized()) {
			Lang::language(\WDDSocial\UserSession::user_lang());
		}
	}
}