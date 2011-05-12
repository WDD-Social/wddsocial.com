<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class AjaxTest implements \Framework5\IExecutable {
	
	public function execute() {
		# site header
		echo render(':template', array('section' => 'top', 'title' => 'AJAX Test'));
			
		echo render(':section', array('section' => 'begin_content'));
		
		echo "<h1>AjaxTest</h1>";
		
		# display site footer
		echo render(':section', array('section' => 'end_content'));
		
		echo render(':template', array('section' => 'bottom'));
	}
}