<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ContactPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# display site header
		echo render('wddsocial.view.WDDSocial\TemplateView', 
			array('section' => 'top', 'title' => 'Contact'));
		
		
		# display site footer
		echo render('wddsocial.view.WDDSocial\TemplateView', 
			array('section' => 'bottom'));
		
	}
}