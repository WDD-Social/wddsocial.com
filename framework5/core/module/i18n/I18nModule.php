<?php

namespace I18n;

/**
* Framework5 internationalization module loader
*/

class I18nModule extends \Framework5\Module implements \Framework5\IModule {
	
	public static function init() {
		
		# import required resources
		#TODO change to parent::module_import(),
		import('core.module.i18n.interface.ILanguagePack');
		import('core.module.i18n.Lang');
		
	}
}