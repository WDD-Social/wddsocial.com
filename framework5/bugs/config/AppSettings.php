<?php

namespace Framework5\Bugs;

/*
* Application configuration file
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class AppSettings {
	
	# package alias' can be used in place of a package name
	public static $package_aliases = array(
		':db' => 'core.controller.Framework5\Database',
		':template' => 'bugs.view.Framework5\Bugs\Template'
	);
}