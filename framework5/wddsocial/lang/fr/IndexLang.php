<?php

/*
* Sample language pack, French
*/

class IndexLang implements \Framework5\ILanguagePack {
	
	public static function content($var) {
		return array(
			'welcome' => "bonjour, {$var['name']}!",
		);
	}
}