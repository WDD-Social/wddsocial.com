<?php

namespace WDDSocial;

/*
* Sample script 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class UserPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# display site header
		echo render('wddsocial.view.WDDSocial\TemplateView', 
			array('section' => 'top', 'title' => 'User Profile'));
		
		# load language pack
		lang_load('wddsocial.lang.WDDSocial\ProfileLang');
		
		# display i18n text
		echo text('ProfileLang:intro', array(
			'name' => 'Tyler',
			'age' => '19',
			'location' => 'New Jersey',
			'month' => 'jan', 'year' => '2009')
		);
		
		# display site footer
		echo render('wddsocial.view.WDDSocial\TemplateView', array('section' => 'bottom'));
		
		
	}
}