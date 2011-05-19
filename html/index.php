<?php

namespace Framework5;

/** 
* Framework5 Front Controller
* 	all http requests are handled by this file.
* 	the uri is retrieved by the Request class, and maps the uri array
* 	to one or more Application controller. the execute methood is
* 	then called on the controller. note that the Application also 
* 	handles all Exceptions caught during execution.
*/

try {
	
	# load Framework5
	require_once '../framework5/autoload.php';
	
	# set site time zone
	date_default_timezone_set('America/New_York');
	
	# get application controller from Framework5\Router, base on the uri segment
	$package_name = Router::resolve(Request::segment(0));
	
	# get application fully qualified name
	$package = new Package($package_name);
	
	# check if the application is a valid package
	if ($package->path_valid()) {
		
		# import the application
		import($package_name);
		
		# define the controller reference
		$app = $package->fully_qualified; 
		
		# check if class implements IApplication
		if (!implement($app, 'Framework5\IApplication'))
			throw new Exception('app must implement Framework5\IApplication');
		
		# execute the application
		debug('Application execution starting');
		execute($package_name);
		debug('Application execution complete');
		
		# log execution stats
		if (DEBUG_MODE and Settings::$log_execution)
			Logger::log_execution();
		
		# log debug information
		if (DEBUG_MODE and Settings::$log_debug) 
			Logger::log_debug(Debugger::dump());
		
	}
	
	# the application is not a valid package
	else {
		echo "Framework5 Front Controller could not import package '$app', configured in Framework5\Router";
		die;
	}
}

catch (Exception $e) {
	# handle exceptions through the application
	if ($app) $app::exception_handler($e);
}