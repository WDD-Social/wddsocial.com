<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class PeoplePage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# load language pack
		//lang_load('wddsocial.lang.TemplateLang');
		
		$user->id = 1;
		$user->typeID = 1;
		$user->firstName = 'Anthony';
		$user->lastName = 'Colangelo';
		$user->vanityURL = 'anthony';
		$user->avatar = 'c4ca4238a0b923820dcc509a6f75849b';
		$user->languageID = 'en';
		
		session_start();
		$_SESSION['user'] = $user;
		$_SESSION['authorized'] = true;
		
		
		echo render('wddsocial.view.TemplateView', array('section' => 'top', 'title' => 'User Profile'));
		echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
		
	}
}