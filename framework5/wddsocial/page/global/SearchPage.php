<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SearchPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => 'Search'));
		
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
		
	}
}