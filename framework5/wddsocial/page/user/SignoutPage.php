<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SignoutPage implements \Framework5\IExecutable {
	
	public static function execute() {
		import('wddsocial.controller.WDDSocial\UserSession');
		UserSession::signout();
		redirect('/');
	}
}