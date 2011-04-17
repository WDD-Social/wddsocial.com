<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class JobsPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# display site header
		echo render('wddsocial.view.TemplateView', array('section' => 'top', 'title' => 'About'));
		
		
		# display site footer
		echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
		
	}
}