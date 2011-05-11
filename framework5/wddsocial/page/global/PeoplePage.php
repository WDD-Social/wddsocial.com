<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class PeoplePage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
	}
	
	
	
	public function execute() {
		
		# display site header
		echo render(':template', 
			array('section' => 'top', 'title' => 'People', 'extra' => 'directory_sorters', 'extra_options' => '/people/'));
		
		echo render(':section', array('section' => 'begin_content'));
		
		$sorter = \Framework5\Request::segment(1);
		
		if (isset($sorter) and ($sorter == 'alphabetically' or $sorter == 'newest' or $sorter == 'oldest'))
			$active = $sorter;
		else 
			$active = 'alphabetically';
		
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'directory', 
				'classes' => array('mega', 'with-secondary'), 
				'header' => 'People', 'extra' => 'directory_sorters', 'extra_options' => array('base_link' => '/people/', 'active' => $active)));
		
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', 
			array('section' => 'bottom'));
		
	}
}