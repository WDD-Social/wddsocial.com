<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class MessagesPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		UserSession::protect();
		
		# display site header
		echo render(':template', 
			array('section' => 'top', 'title' => 'Messages'));
		
		
		# display site footer
		echo render(':template', 
			array('section' => 'bottom'));
		
	}
}