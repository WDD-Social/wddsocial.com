<?php

namespace Framework5;

/*
* Framework5 configuration file
*/

class Settings {
	
	# current mode
	const PRODUCTION_MODE = 0; # Development 0, Production 1;
	
	# execution stats and debug logging
	
	public static $debug_mode = true;
	public static $log_debug = true;
	public static $log_execution = true;
	public static $log_exception = true;
	
	# connection for database used by core framework classes
	public static $dbinfo = array(
		'host' => 'localhost',
		'username' => 'root',
		'password' => 'root',
		'dbname' => 'sandbox'
	);
	
	# package alias' can be used in place of a package name
	public static $package_aliases = array(
		':db' => 'wddsocial.controller.WDDSocial\Database'
	);
}