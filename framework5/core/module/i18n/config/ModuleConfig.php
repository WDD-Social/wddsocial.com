<?php

namespace Framework5;

/*
* Framework5 configuration file
*/

class ModuleConfig {
	
	# languages supported
	public static $languages = array(
		'en' => 'English',
		'es' => 'Spanish',
		'fr' => 'French'
	);
	
	# the default language
	public static $default_language = 'en';
	
	# used for language pack autoloading
	public static $language_packs = array(
		'DateLang' => 'wddsocial.lang.DateLang',
		
	);
}