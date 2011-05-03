<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class AccountPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# display site header
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'top', 'title' => 'My Account'));
		
		echo "<pre>";
		print_r($_SESSION['user']);
		echo "</pre>";
		
		# display site footer
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
		
	}
}