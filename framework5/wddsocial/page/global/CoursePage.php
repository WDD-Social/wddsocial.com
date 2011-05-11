<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class CoursePage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => 'Courses'));
		
		# display list of all courses
		
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
		
	}
}