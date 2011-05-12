<?php

namespace WDDSocial;

/*
*
* @author Anthony Colangelo (me@acolangelo.com) 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class JobsPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
	}
	
	
	
	public function execute() {
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => 'Jobs'));
		
		echo render(':section', array('section' => 'begin_content'));
		
		
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
		
	}
}