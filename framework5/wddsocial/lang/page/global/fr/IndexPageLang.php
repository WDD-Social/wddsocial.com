<?php

/*
* IndexPage Language Pack - French
* 
* @author tmatthewsdev (tmatthewsdev@gmail.com)
* @translator 
*/

class IndexPageLang implements \Framework5\ILanguagePack {
	
	public static function content($var) {
		return array(
			'welcome' => "bonjour, {$var['name']}!",
		);
	}
}