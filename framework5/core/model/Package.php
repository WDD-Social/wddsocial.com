<?php

namespace Framework5;

/*
* Controller to access properties of a package name
*/

class Package extends Controller {
	
	private $_info = array(
		'package_name'		=> null,
		'path_array'		=> null,
		'package_base'		=> null,
		'path'				=> null,
		'fully_qualified'	=> null,
		'class'				=> null,
		'namespace'			=> null
	);
	
	public function __construct($package_name) {
		$this->_info = PackageManager::package_info($package_name);
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
	*/
	
	public function path_valid() {
		if (!file_exists($this->path)) return false;
		return true;
	}
}