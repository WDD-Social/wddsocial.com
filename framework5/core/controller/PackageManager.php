<?php

namespace Framework5;

/**
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class PackageManager extends StaticController {
	
	private static $_package_cache = array();
	private static $_alias_stack = array();
	
	
	
	public static function package_info($package_name) {
		
		# resolve package alias
		if (static::is_alias_format($package_name))
			$package_name = static::resolve_package_alias($package_name);
		
		# check for cached value
		if (array_key_exists($package_name, static::$_package_cache)) {
			return static::$_package_cache[$package_name];
		}
		
		# set package properties]
		$package = array();
		$package['package_name'] = $package_name;
		$package['path_array'] = explode('.', $package_name);
		$package['fully_qualified'] = end($package['path_array']);
		
		# set package_base
		$package_base_array = array();
		for($i = 0; $i < count($package['path_array']) - 1; $i++)
			array_push($package_base_array, $package['path_array'][$i]);
			$package['package_base'] = implode('.', $package_base_array);
		
		# if namespace exists
		$fully_qualified_array = explode('\\', $package['fully_qualified']);
		if (count($fully_qualified_array) > 1) {
			
			# get the class name
			$package['class'] = end($fully_qualified_array);
			
			# get the namespace
			$namespace_array = array();
			for($i = 0; $i < count($fully_qualified_array) - 1; $i++)
				array_push($namespace_array, $fully_qualified_array[$i]);
			
			$package['namespace'] = implode('\\', $namespace_array);
			
			# get the path of the package
			$actual_path = array();
			for($i = 0; $i < count($package['path_array']) - 1; $i++)
				array_push($actual_path, $package['path_array'][$i]);
			
			array_push($actual_path, $package['class']);
			$package['path'] = PATH_FRAMEWORK . implode('/', $actual_path) . EXT;
		}
		
		# no namespace
		else {
			$package['class'] = $fully_qualified_array[0];
			$package['path'] = PATH_FRAMEWORK . implode('/', $package['path_array']) . EXT;
		}
		
		# cache information
		static::$_package_cache[$package_name] = $package;
		
		return $package;
	}
	
	
	
	/**
	* Define a package alias
	*/
	
	public static function define_alias($alias, $package_name) {
		
		# check if valid alias format
		if (!static::is_alias_format($alias))
			throw new Exception("Could not define package alias '$alias', not valid format");
		
		# check if already defined
		if (static::is_package_alias($alias))
			throw new Exception("Could not define package alias '$alias', already defined");
		
		static::$_alias_stack[$alias] = $package_name;
		
		return true;
	}
	
	
	
	/**
	* Define an array of package aliases, key value pair
	* 
	*/
	
	public static function define_alias_array($alias_array) {
		
		foreach ($alias_array as $alias => $package_name) {
			# check if valid alias format
			if (!static::is_alias_format($alias))
				throw new Exception("Could not define package alias '$alias', not valid format");
			
			# check if already defined
			if (static::is_package_alias($alias))
				throw new Exception("Could not define package alias '$alias', already defined");
			
			static::$_alias_stack[$alias] = $package_name;
		}
		
		return true;
	}
	
	
	
	/**
	* Determines if the given package name is an alias
	* 
	* @param string package
	* @return boolean
	*/
	
	final public static function is_package_alias($alias) {
				
		# check alias format (must start with colon)
		if (!static::is_alias_format($alias)) return false;
				
		# check if the alias is defined
		if (!array_key_exists($alias, static::$_alias_stack)) return false;
		
		return true;
	}
	
	
	
	/**
	* Gets a package name from a package alias
	* 
	* @param string alias
	*/
	
	final public static function resolve_package_alias($alias) {
		
		# if the alias is not defined
		if (!static::is_package_alias($alias)) {
			throw new Exception("Could not resolve undefined package alias '$alias'");
		}
				
		return static::$_alias_stack[$alias];
	}
	
	
	
	
	final public static function is_alias_format($alias) {
		# the first character in an alias is a colon
		# the first character in an alias is a colon
		if(substr($alias, 0, 1) !== ':') {
			return false;
		}
		return true;
	}
}