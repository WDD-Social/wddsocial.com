<?php

namespace Ajax;

/*
* Application configuration file
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class AjaxSettings {
	# package alias' can be used in place of a package name
	public static $package_aliases = array(
		':db' => 'wddsocial.controller.WDDSocial\Database',
		':sel-sql' => 'wddsocial.sql.WDDSocial\SelectorSQL',
		':val-sql' => 'wddsocial.sql.WDDSocial\ValidatorSQL',
		':admin-sql' => 'wddsocial.sql.WDDSocial\AdminSQL'
	);
}