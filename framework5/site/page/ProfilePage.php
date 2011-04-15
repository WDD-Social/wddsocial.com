<?php

/*
* Sample script 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ProfilePage  implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# sample use of a site controller
		import('site.controller.Profile');
		
		
		# static method call
		if (Profile::is_even(2)) {
			echo 'even';
		}
		else {
			echo 'odd';
		}
		
		
	}
}