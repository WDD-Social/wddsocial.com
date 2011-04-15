<?php

namespace WDDSocial;

/*
* Sample script 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ProfilePage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# sample use of a site controller
		import('site.controller.WDDSocial\Profile');
		
		
		# load language pack
		lang_load('wddsocial.lang.ProfileLang');
		
		
		echo text('ProfileLang:intro', array(
			'name' => 'Tyler',
			'age' => '19',
			'location' => 'New Jersey',
			'month' => 'jan', 'year' => '2009')
		);
		
		
				
	}
}