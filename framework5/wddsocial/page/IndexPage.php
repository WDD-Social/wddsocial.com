<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public static function execute() {
		echo "{index page}<br/>";
		
		# load language pack
		//lang_load('wddsocial.lang.TemplateLang');
		
		
		
		echo '{navigation}';
		//echo text('TemplateLang:people');
		
		
	}
}