<?php

namespace Framework5;

/*
* Controller to access properties of a package name
*/

class Package {
	
	private $_info = array(
		'package_name'		=> null,
		'path_array'		=> null,
		'package_base'		=> null,
		'path'				=> null,
		'fully_qualified'	=> null,
		'class'				=> null,
		'namespace'			=> null
	);
	
	private static $_cache = array();
	
	
	
	public function __construct($package_name) {
		
		# resolve package alias
		if(static::_is_package_alias($package_name))
			$package_name = static::_resolve_package_alias($package_name);
		
		
		# check for cached value
		if (array_key_exists($package_name, Package::$_cache)) {
			$this->_info = Package::$_cache[$package_name];
			return true;
		}
		
		# set package properties
		$this->package_name = $package_name;
		$this->path_array = explode('.', $package_name);
		$this->fully_qualified = end($this->path_array);
		
		# set package_base
		$package_base_array = array();
		for($i = 0; $i < count($this->path_array) - 1; $i++)
			array_push($package_base_array, $this->path_array[$i]);
			$this->package_base = implode('.', $package_base_array);
		
		# if namespace exists
		$fully_qualified_array = explode('\\', $this->fully_qualified);
		if (count($fully_qualified_array) > 1) {
			
			# get the class name
			$this->class = end($fully_qualified_array);
			
			# get the namespace
			$namespace_array = array();
			for($i = 0; $i < count($fully_qualified_array) - 1; $i++)
				array_push($namespace_array, $fully_qualified_array[$i]);
			
			$this->namespace = implode('\\', $namespace_array);
			
			# get the path of the package
			$actual_path = array();
			for($i = 0; $i < count($this->path_array) - 1; $i++)
				array_push($actual_path, $this->path_array[$i]);
			
			array_push($actual_path, $this->class);
			$this->path = PATH_FRAMEWORK . implode('/', $actual_path) . EXT;
		}
		
		# no namespace
		else {
			$this->class = $fully_qualified_array[0];
			$this->path = PATH_FRAMEWORK . implode('/', $this->path_array) . EXT;
		}
		
		# cache information
		Package::$_cache[$package_name] = $this->_info;
		
		return true;
	}
	
	
	
	public function __get($key) {
		if (!array_key_exists($key, $this->_info))
			throw new \Exception("Attempted to access invlaid package property '$key'");
		return $this->_info[$key];
	}
	
	
	
	public function __set($key, $value) {
		if (!array_key_exists($key, $this->_info))
			throw new \Exception("Attempted to set invlaid package property '$key'");
		$this->_info[$key] = $value;
	}
	
	
	
	/**
	* Determines if the packages file path is valid
	* 
	*/
	public function path_valid() {
		if (!file_exists($this->path)) return false;
		return true;
	}
	
	
	
	/**
	* Determines if the given package name is an alias
	* 
	* @param string package
	* @return boolean
	*/
	
	final private static function _is_package_alias($package_name) {
		
		# the first character in an alias is a colon
		if(substr($package_name, 0, 1) !== ':') {
			return false;
		}
		return true;
	}
	
	
	
	/**
	* Gets a package name from a package alias
	* 
	* @param string alias
	*/
	
	final private static function _resolve_package_alias($alias) {
		
		# if the alias name is not valid format (does not start with :)
		if (!static::_package_is_alias($alias)) {
			throw new Exception("Could not resolve alias '$alias'. Not a valid alias");
		}
		
		# if the alias array is not defined
		if (!isset(Settings::$package_aliases)) {
			throw new Exception("Could not resolve alias '$alias'. Package alias configuration missing.");
		}
		
		# if the alias is not defined
		if (!array_key_exists($alias, Settings::$package_aliases)) {
			throw new Exception("Could not resolve invalid alias '$alias'");
		}
		
		return Settings::$package_aliases[$alias];
	}
}