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
		
		import('wddsocial.controller.UserValidator');
		import('wddsocial.sql.SelectorSQL');
				
		# enable localization module
		import('core.module.i18n.Lang');
		Lang::language($lang);
		
		# resolve request to a page controller
		import('wddsocial.config.Router');
		$package = \WDDSocial\Router::resolve(Request::segment(0));
		
		# check user session
		import('wddsocial.controller.UserSession');
		\WDDSocial\UserSession::status();
		
		# execute the controller
		execute($package);
		return true;
	}
}