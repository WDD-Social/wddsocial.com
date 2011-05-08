<?php

namespace Framework5;

/**
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class PackageManager extends StaticController {
	
	
	private static $_alias_stack = array();
	
	
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