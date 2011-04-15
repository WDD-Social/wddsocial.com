<?php

//namespace Framework5;

/**
* Framework5 internationalization module loader
*/

final class LocalizationModule extends \Framework5\Module implements \Framework5\IModule {
	
	private static $_module_info = array(
		'name' 			=> 'LocalizationModule',
		'description' 	=> '',
		'authors' 		=> 'Tyler Matthews',
		'version' 		=> '0.1.0',
		'package'		=> 'core.module.localization'
	);
	
	
	
	public static function execute() {
	
		/* TODO change to from Module::module_import
		module_import('config.LanguageSettings');
		module_import('interface.ILanguagePack');
		module_import('controller.Language');
		module_import('functions.global');
		*/
		
		# import required classes
		import('core.module.localization.config.LocalizationSettings');
		import('core.module.localization.interface.ILanguagePack');
		import('core.module.localization.controller.Localization');
		import('core.module.localization.functions.global');
		
		# determine if valid languages array is set in the settings file
		if (!isset(\Framework5\LocalizationSettings::$languages) or empty(\Framework5\LocalizationSettings::$languages))
			throw new Exception(
				"Could not import localization module. Valid languages array must be set in LanguageSettings");
		
	}
}