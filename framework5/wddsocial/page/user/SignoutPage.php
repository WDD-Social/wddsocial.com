<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SignOutPage implements \Framework5\IExecutable {
	
	public static function execute() {
		import('wddsocial.controller.WDDSocial\UserSession');
		\WDDSocial\UserSession::fake_user_logout();
		
		header('Location: http://localhost:8888');
	}
}