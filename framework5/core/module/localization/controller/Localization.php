<?php

namespace Framework5;

/*
* Framework5 internationalization support
* 
* this package is currently in development and is not yet documented
*/

class Localization {
	
	private static $_imported_lang_packs = array();
	
	private static $_language; # the current language id (en,es,fr)
	
	
	
	/**
	* Set the current language
	*/
	
	public static function lang_set($language) {
		if (!in_array($language, array_keys(LocalizationSettings::$languages)))
			throw new Exception("Language could not be set to '$language', not a valid language id");
		static::$_language = $language;
		return true;
	}
	
	
	
	/**
	* Returns the current or default language
	*/
	
	public static function lang_get() {
		if (!isset(static::$_language) or empty(static::$_language)) 
			return LocalizationSettings::$default_language;
		return static::$_language;
	}
	
	
	
	/**
	* Returns formatted content from a Language Package
	* 
	* example: text('SiteLang:welcome', array('name' => 'Tyler'));
	*/
	
	public static function text($selector, $var = null) {
		$info = explode(':', $selector);
		
		$class = $info[0];
		$id = $info[1];
		
		# determine if the class has ben loaded
		if (!static::lang_class_loaded($class)) {
			
			# try to autoload the class
			if (!static::_lang_autoload($class))
				throw new Exception("Language Pack '$class' not found");
		}
		
		return $class::content($id, $var);
	}
	
	
	
	/**
	* Load a Language Package
	*/
	
	public static function lang_load($package) {
		
		# strip base and class from package
		$base = package_base($package);
		$lang = static::lang_get();
		$class = package_class($package);
		
		# construct package location with current or default language
		$package = "$base.$lang.$class";
		
		debug("Language loading package '$package'");
		
		# check if the package has already been loaded
		if (in_array($class, array_keys(static::$_imported_lang_packs)))
			throw new Exception("Duplicate language pack '$class'");
		
		# if the package is not valid, throw exception
		if (!package($package))
			throw new Exception("Language pack '$package' could not be loaded because it does not exist");
		
		# import the language package
		import($package);
		
		# add language classname to array
		static::$_imported_lang_packs[$class] = $package;
		
	}
	
	
	
	/**
	* Determine if a Language Package has been loaded
	*/
	
	public static function lang_package_loaded($package) {
		if (in_array($class, array_values(static::$_imported_lang_packs))) return true;
		return false;
	}
	
	
	
	/**
	* Determine if a Language Package class name has been loaded
	*/
	
	public static function lang_class_loaded($class) {
		if (in_array($class, array_keys(static::$_imported_lang_packs))) return true;
		return false;
	}
	
	
	
	/**
	* Attempts to autoload an undefined Language Package before throwing an Exception
	*/
	
	private static function _lang_autoload($class) {
		
		# check if the autoload packages are defined
		if (!isset(LocalizationSettings::$language_packs))
			return false;
		
		if (!array_key_exists($class, LocalizationSettings::$language_packs))
			return false;
		
		lang_load(LocalizationSettings::$language_packs[$class]);
		return true;
	}
}