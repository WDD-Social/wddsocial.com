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
		
		# enable localization module
		//load_module('core.module.i18n.I18n\I18nModule');
		import('core.module.i18n.Lang');
		Lang::language('en');
		
		# testing
		$user->id = 1;
		$user->typeID = 1;
		$user->firstName = 'Anthony';
		$user->lastName = 'Colangelo';
		$user->vanityURL = 'anthony';
		$user->avatar = '24c9e15e52afc47c225b757e7bee1f9d';
		$user->languageID = 'en';
		session_start();
		$_SESSION['user'] = $user;
		$_SESSION['authorized'] = true;
		
		# determine if the user is logged in
		//Session::logged_in();
		
		# resolve request to a page controller
		import('wddsocial.config.Router');
		$package = \WDDSocial\Router::resolve(Request::segment(0));
		
		# execute the controller
		execute($package);
		return true;
	}
}