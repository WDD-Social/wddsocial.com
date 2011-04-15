<?php

namespace Framework5;

/**
* boot Framework5 via an anonymous function call
* 	this file should not be modified
*/

call_user_func(function() {
	
	# general settings
	define('EXEC_START_TIME', number_format(microtime(true), 8, '.', ''));
	define('EXT', '.php'); # default php extension
	define('DEVELOPMENT', 0);
	define('PRODUCTION', 1);
	
	# configure absolute paths
	define('PATH_FRAMEWORK', __DIR__.DIRECTORY_SEPARATOR);
	define('PATH_CORE', PATH_FRAMEWORK.'core/');
	define('PATH_SITE', PATH_FRAMEWORK.'site/');
	define('PATH_DOCS', PATH_FRAMEWORK.'docs/');
	
	# framework interfaces
	require PATH_CORE.'interface/IExecutable.php';
	require PATH_CORE.'interface/IApplication.php';
	require PATH_CORE.'interface/IModule.php';
	require PATH_CORE.'interface/IRouter.php';
	require PATH_CORE.'interface/IView.php';
		
	# framework core files
	require PATH_CORE.'controller/Controller.php'; # controller base
	require PATH_CORE.'exception/Exception.php'; # custom exception
	require PATH_CORE.'config/Settings.php'; # configuration file
	require PATH_CORE.'config/Router.php'; # primary routing file
	require PATH_CORE.'controller/Package.php'; # 
	require PATH_CORE.'controller/Factory.php'; # framework factory
	require PATH_CORE.'controller/Request.php'; # request object
	require PATH_CORE.'controller/Logger.php'; # log utility
	require PATH_CORE.'controller/Module.php'; # module base
	require PATH_CORE.'controller/View.php'; # view object
	
	# application dependencies
	require PATH_CORE.'controller/ApplicationBase.php';
	
	# debug utility
	if (Settings::$debug_mode) { 
		require PATH_CORE.'controller/Debugger.php';
		require PATH_CORE.'model/DebugMessage.php';
	}
	
	# framework global functions
	require PATH_CORE.'functions/global.php';
	
	# exit
	debug('Framework5 initialized');
	return true;
});