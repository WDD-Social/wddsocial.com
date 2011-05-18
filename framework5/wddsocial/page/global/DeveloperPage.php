<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class DeveloperPage implements \Framework5\IExecutable {
	
	public function execute() {
		$content = " ";
		
		# display page
		echo render(':template', 
			array('title' => 'Developer', 'content' => $content));
	}
}