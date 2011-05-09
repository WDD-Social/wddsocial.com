<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class TermsPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => 'Terms'));
		
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
		
	}
}