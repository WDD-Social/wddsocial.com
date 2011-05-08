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
		
		# import settings and load 
		import('wddsocial.config.WDDSocial\AppSettings');
		PackageManager::define_alias_array(\WDDSocial\AppSettings::$package_aliases);
		
		# import application global dependencies
		import('wddsocial.controller.WDDSocial\UserValidator');
		import('wddsocial.helper.WDDSocial\NaturalLanguage');
		import('wddsocial.sql.WDDSocial\SelectorSQL');
		
		# check user session
		import('wddsocial.controller.WDDSocial\UserSession');
		\WDDSocial\UserSession::init();
		
		# enable localization module
		import('core.module.i18n.Framework5\Lang');
		Lang::language('en');
		
		# resolve request to a page controller
		import('wddsocial.config.WDDSocial\Router');
		$package = \WDDSocial\Router::resolve(Request::segment(0));
		
		# execute the controller
		execute($package);
		return true;
	}
}