<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ContactPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# display site header
		echo render(':template', 
			array('section' => 'top', 'title' => 'Contact'));
		
		throw new \Framework5\Exception("TEST");
		# display site footer
		echo render(':template', 
			array('section' => 'bottom'));
		
	}
}