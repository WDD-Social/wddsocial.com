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
	
	public static function execute() {

		# import application global dependencies
		import('wddsocial.controller.WDDSocial\UserValidator');
		import('wddsocial.helper.WDDSocial\NaturalLanguage');
		import('wddsocial.sql.WDDSocial\SelectorSQL');
		
		# enable localization module
		import('core.module.i18n.Framework5\Lang');
		Lang::language('en');
		
		# resolve request to a page controller
		import('wddsocial.config.WDDSocial\Router');
		$package = \WDDSocial\Router::resolve(Request::segment(0));
		
		# check user session
		import('wddsocial.controller.WDDSocial\UserSession');
		\WDDSocial\UserSession::init();
		\WDDSocial\UserSession::fake_user_signin(2); #TMP
		
		# execute the controller
		execute($package);
		return true;
	}
}