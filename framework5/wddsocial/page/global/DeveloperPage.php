<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class DeveloperPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# display site header
		echo render(':template', 
			array('section' => 'top', 'title' => 'Developer'));
		
		
		# display site footer
		echo render(':template', 
			array('section' => 'bottom'));
		
	}
}