<?php

namespace Framework5;

/**
* Framework5 internationalization module loader
*/

class I18n extends Module implements IModule {
	
	/** use:
	
	I18n::language('en');
	
	
	*/
	
	
	public static function init() {
		
		# set module properties
		$_module_info = array(
			'name' 			=> 'I18n',
			'description' 	=> 'Language localization module',
			'authors' 		=> 'Tyler Matthews',
			'version' 		=> '0.3.0',
			'package'		=> 'core.module.i18n.I18n'
		);
		
		
		# import required resources
		#TODO change to parent::module_import(),
		import('core.module.i18n.config.ModuleConfig');
		import('core.module.i18n.interface.ILanguagePack');
		import('core.module.i18n.functions.global');
		
		# determine if valid languages array is set in the settings file
		if (!isset(ModuleConfig::$languages) 
		or empty(ModuleConfig::$languages))
		throw new Exception(
			"Could not import I18n module. Valid languages array must be set in config.ModuleConfig");
		
		
		
	}
	
	
	
	private static $_imported_lang_packs = array();
	
	private static $_language; # the current language id (en,es,fr)
	
	
		
	/**
	* Set or get current language
	*/
	
	public static function language($language = null) {
		if ($language) {
			if (!in_array($language, array_keys(ModuleConfig::$languages)))
				throw new Exception("Language could not be set to '$language', not a valid language id");
			static::$_language = $language;
			return true;
		}
		
		else {
			if (!isset(static::$_language) or empty(static::$_language)) 
				return ModuleConfig::$default_language;
			
			return static::$_language;
		}
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
		$lang = static::language();
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
		if (!isset(ModuleConfig::$language_packs))
			return false;
		
		if (!array_key_exists($class, ModuleConfig::$language_packs))
			return false;
		
		lang_load(ModuleConfig::$language_packs[$class]);
		return true;
	}
}