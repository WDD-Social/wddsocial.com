<?php

/**
* Language module functions
* 	
*/

# get a text string in the current language
if (!function_exists('text')) {
	 function text($id, $var = null) {
	 	return \Framework5\I18n::text($id, $var);
	}
}
else{
	throw new Exception("Could not redefine function text() in core.module.i18n");
}

# set the current language
if (!function_exists('language')) {
	function language($language = null) {
		return \Framework5\I18n::language($language);
	}
}
else{
	throw new Exception("Could not redefine function lang_set() in core.module.i18n");
}

# load a language pack
if (!function_exists('lang_load')) {
	function lang_load($package) {
		return \Framework5\I18n::lang_load($package);
	}
}
else{
	throw new Exception("Could not redefine function lang_load() in core.module.i18n");
}
