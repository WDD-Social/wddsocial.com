<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class AboutPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# display site header
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'top', 'title' => 'About'));
		
		
		# display site footer
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
		
	}
}