<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public function execute() {
		
		
		$content = " ";
		
		# display output
		echo render(':template',
			array('title' => 'Execute Command', 'content' => $content));
	}
}