<?php

namespace Framework5;

/*
* The Factory class handles package methods and instances 
*/

class Factory extends Controller {
	
	private static $_controllers; # object container
	private static $_loaded_controllers = array(); # package names of _controllers
	private static $_imported_paths = array(); # package names of all imported packages
	
	/**
	* Imports a package or alias
	* 
	* @package string package
	* @return boolean
	*/
	
	final public static function import($package_name) {
		
		debug("Factory importing package '$package_name'");
		
		# get package information
		$package = new Package($package_name);
		
		# determine if location has already been imported
		if (static::loaded($package_name)) return true;
		
		
		# check valid path
		if (!$package->path_valid()) {
			$message = "Factory could not load package '$package_name'. because the file does not exist.";
			debug($message);
			throw(new Exception($message));
		}
		
		# import the file
		require $package->path;
		
		# add location to array
		array_push(static::$_imported_paths, $package->path);
		return true;
	}
	
	
	
	/**
	* Determines if a package has been loaded
	* 
	* @param string package
	*/
	
	final public static function loaded($package_name) {
		
		# get package information
		$package = new Package($package_name);
		
		if (!in_array($package->path, static::$_imported_paths)) {
			return false;
		}
		return true;
	}
	
	
	
	/**
	* Returns a factory instance of a given package or alias
	* 
	* @param string package
	* @return object
	*/
	
	final public static function instance($package_name) {
		
		# check if package is already loaded
		if (static::instance_loaded($package_name)) {
			debug("Factory retrieved instance of '$package_name'");
			return static::$_controllers[$package_name];
		}
		
		# create a new instance of the class
		else {
			static::import($package_name);
			
			# get the classname and create a new instance
			$package = new Package($package_name);
			$controller = $package->fully_qualified;
			
			static::$_controllers[$package_name] = new $controller();
			
			array_push(static::$_loaded_controllers, $package_name);
			
			debug("Factory created instance of '$package_name'");
			return static::$_controllers[$package_name];
		}
	}
	
	
	
	/**
	* Returns true is a given package instance exists
	* 
	* @param string package
	* @return bool
	*/
	
	final public static function instance_loaded($package_name) {
		if (!in_array($package_name, static::$_loaded_controllers)) {
			return false;
		}
		
		return true;
	}
	
	
	
	/**
	* Executes a package and returns the result
	* scripts must implement interface IExecutable
	*/
	
	public function execute($package_name, $options = null) {
		
		debug("Executing package '$package_name'");
		
		# if the package has not been loaded, import it
		if (!loaded($package_name)) import($package_name);
		
		# get the class name
		$package = new Package($package_name);
		$controller = $package->fully_qualified;
		
		# check if class implents the Framework5\IScript interface
		if (!Factory::implement($controller, 'Framework5\IExecutable'))
			throw new Exception("Package '$package_name' could not be executed, class '$controller' does not implement interface '\Framework5\IExecutable'");
		
		# execute and return the result
		return $controller::execute($options);
	}
	
	
	
	/**
	* Returns true if the given class implements the given namespace
	* 
	* @param object
	* @param string interface
	* @retun boolean
	*/
	
	final public static function implement($class, $interface) {
		
		# determine if the passed exists
		if (!class_exists($class))
			throw new Exception("Could not determine if class '$class' implements interface '$interface' because '$class' does not exist");
		
		# use the reflection class to determine if the class implements the interface
		$reflection = new \ReflectionClass($class);
		if(!in_array($interface, $reflection->getInterfaceNames())) {
			return false;
		}
		return true;
	}
}	