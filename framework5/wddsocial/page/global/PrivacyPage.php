<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class PrivacyPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# display site header
		echo render(':template', 
			array('section' => 'top', 'title' => 'Privacy'));
		
		
		# display site footer
		echo render(':template', 
			array('section' => 'bottom'));
		
	}
}