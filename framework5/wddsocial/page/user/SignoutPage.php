<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SignoutPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# display site header
		echo render('wddsocial.view.TemplateView', array('section' => 'top', 'title' => 'Sign Out'));
		
		
		# display site footer
		echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
		
	}
}