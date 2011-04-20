<?php

namespace Framework5;

/**
* Framework5 internationalization module
*/

class Lang extends Controller {
	
	# languages supported
	private static $_languages = array(
		'en' => 'English',
		'es' => 'Spanish',
		'fr' => 'French');
	
	private static $_default_language = 'en'; # the default language
	private static $_language; # the current language id (en,es,fr)
	
	
	
	private $_current_package; # holds the package that was passed to the constructor
	
	public function __construct($package_name) {
		
		# get the package name in the local language directory
		$package = new Package($package_name);
		$lang = static::language();
		$local_package = "{$package->package_base}.$lang.{$package->class}";
		
		# if the package is not valid, throw exception
		if (!package($local_package))
			throw new Exception("Language pack '$package_name' could not be loaded in local language '$local_package' because it does not exist");
		
		# if the package is imported
		if (!loaded($local_package)) import($local_package);
		
		# save the local Package object
		$this->_current_package = $package;
		
	}
	
	public function __destruct() {
		
	}
	
	/**
	* Returns formatted content from a Language Package
	* 
	* example: text('welcome', array('name' => 'Tyler'));
	*/
	
	public function text($selector, $var = null) {
		$class = $this->_current_package->class;
		return $class::content($selector, $var);
	}
	
	
	
	/**
	* Set or get current language
	*/
	
	public static function language($language = null) {
		if ($language) {
			if (!in_array($language, array_keys(static::$_languages)))
				throw new Exception("Language could not be set to '$language', not a valid language id");
			static::$_language = $language;
			return true;
		}
		
		else {
			if (!isset(static::$_language) or empty(static::$_language)) 
				return static::$_default_language;
			return static::$_language;
		}
	}
}

/**
* Framework5 Labguage Pack Interface
*/

interface ILanguagePack {
	public static function content($id, $var);
}