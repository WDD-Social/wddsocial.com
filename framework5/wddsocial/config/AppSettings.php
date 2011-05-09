<?php

namespace WDDSocial;

/*
* Application configuration file
*/

class AppSettings {
	
	# package alias' can be used in place of a package name
	public static $package_aliases = array(
		':db' => 'wddsocial.controller.WDDSocial\Database',
		':sel-sql' => 'wddsocial.sql.WDDSocial\SelectorSQL',
		':val-sql' => 'wddsocial.sql.WDDSocial\ValidatorSQL',
		':admin-sql' => 'wddsocial.sql.WDDSocial\AdminSQL',
		':template' => 'wddsocial.view.global.WDDSocial\TemplateView',
		':section' => 'wddsocial.view.global.WDDSocial\SectionView'
	);
}