<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class CoursesPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# display site header
		echo render('wddsocial.view.TemplateView', array('section' => 'top', 'title' => 'Courses'));
		
		
		# display site footer
		echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
		
	}
}