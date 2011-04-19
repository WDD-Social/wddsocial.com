<?php

/**
* Global framework functions
* 	each function is accessable to all conrollers handled by the framework
*/

# import a package
if (!function_exists('import')) {
	 function import($package_name) {
	 	return \Framework5\Factory::import($package_name);
	}
}

# returns true if the package has baan loaded
if (!function_exists('loaded')) {
	 function loaded($package_name) {
	 	return \Framework5\Factory::loaded($package_name);
	}
}

# returns true if the object implements the interface
if (!function_exists('implement')) {
	function implement($ojbect, $interface) {
		return \Framework5\Factory::implement($ojbect, $interface);
	}
}

# execute a controller
if (!function_exists('execute')) {
	function execute($package_name, $options = null) {
		return \Framework5\Factory::execute($package_name, $options);
	}
}

# import a module
if (!function_exists('load_module')) {
	 function load_module($package_name) {
	 	return \Framework5\Factory::load_module($package_name);
	}
}

# render a view
if (!function_exists('render')) {
	function render($package_name, $options = null) {
		return \Framework5\View::render($package_name, $options);
	}
}

# returns a single instance of a package
if (!function_exists('instance')) {
	function instance($package_name) {
		return \Framework5\Factory::instance($package_name);
	}
}

# returns the classname of a given package
if (!function_exists('package_class')) {
	function package_class($package_name) {
		$package = new \Framework5\Package($package_name);
		return $package->class;
	}
}

# returns the base location of a given package
if (!function_exists('package_base')) {
	function package_base($package_name) {
		$package = new \Framework5\Package($package_name);
		return $package->package_base;
	}
}

# returns true if the package is a valid file
if (!function_exists('package')) {
	function package($package_name) {
		$package = new \Framework5\Package($package_name);
		return $package->path_valid();
	}
}

# helper function to determine the value of variables
if (!function_exists('trace')) {
	function trace($message) {
		return \Framework5\Logger::trace($message);
	}
}

# helper function to log the execution of the application
if (!function_exists('debug')) {
	function debug($message) {
		if (!\Framework5\Settings::$debug_mode) {
			return true;
		}
		return \Framework5\Debugger::debug($message, debug_backtrace());
	}
}