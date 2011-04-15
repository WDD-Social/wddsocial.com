<?php

/**
* Language module functions
* 	
*/

# get a text string in the current language
if (!function_exists('text')) {
	 function text($id, $var = null) {
	 	return \Framework5\Localization::text($id, $var);
	}
}
else{
	throw new Exception("Could not redefine function text() in core.functions.language.");
}

# set the current language
if (!function_exists('lang_set')) {
	function lang_set($language) {
		return \Framework5\Localization::lang_set($language);
	}
}
else{
	throw new Exception("Could not redefine function lang_set() in core.functions.language.");
}

# load a language pack
if (!function_exists('lang_load')) {
	function lang_load($package) {
		return \Framework5\Localization::lang_load($package);
	}
}
else{
	throw new Exception("Could not redefine function lang_load() in core.functions.language.");
}
