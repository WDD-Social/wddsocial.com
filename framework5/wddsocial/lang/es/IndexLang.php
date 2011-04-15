<?php

/*
* Sample language pack, Spanish
*/

class IndexLang implements \Framework5\ILanguagePack {
	
	public static function content($var) {
		return array(
			'welcome' => "hola, {$var['name']}!",
		);
	}
}