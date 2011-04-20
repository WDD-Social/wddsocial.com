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
		import('wddsocial.model.UserVO');
		import('wddsocial.sql.SelectorSQL');
		# GET DB INSTANCE AND QUERY
		$db = instance(':db');
		$sql = new \WDDSocial\SelectorSQL();
		$query = $db->prepare($sql->getUserByID);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
		$data = array('id' => 1);
		$query->execute($data);
		$user = $query->fetch();
		
		session_start();
		$_SESSION['user'] = $user;
		$_SESSION['authorized'] = true;
		
		$lang = (\WDDSocial\UserValidator::is_authorized())?$_SESSION['user']->languageID:'en';
		# enable localization module
		//load_module('core.module.i18n.I18n\I18nModule');
		import('core.module.i18n.Lang');
		Lang::language($lang);
		
		# resolve request to a page controller
		import('wddsocial.config.Router');
		$package = \WDDSocial\Router::resolve(Request::segment(0));
		
		# execute the controller
		execute($package);
		return true;
	}
}