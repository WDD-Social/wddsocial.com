<?php

/*
* Sample language pack, English
*/

class IndexLang implements \Framework5\ILanguagePack {
	
	public static function content($var) {
		return array(
			'welcome' => "hello, {$var['name']}!",
			
		);
	}
}