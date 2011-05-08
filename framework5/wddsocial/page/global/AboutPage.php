<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class AboutPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# display site header
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'top', 'title' => 'About'));
		
		$this->member();
		
		# display site footer
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
		
	}
	
	
	private function member() {
		return true;
	}
	
}