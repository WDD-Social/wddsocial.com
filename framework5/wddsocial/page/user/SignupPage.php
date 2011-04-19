<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SignupPage implements \Framework5\IExecutable {
	
	public static function execute() {
		# display site header
		echo render('wddsocial.view.TemplateView', array('section' => 'top', 'title' => 'Sign Up'));
		
		
		# display site footer
		echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
		
	}
}