<?php

namespace Framework5;

/*
* Handles http request information
*/

class Request {

	private static $_request_id;
	private static $_uri;
	private static $_uri_array;
	
	
	
	/**
	* Defines the Request uri property
	* 
	* @return string current request uri
	*/
	
	public static function uri() {
		if (static::$_uri) return static::$_uri;
		return static::$_uri = static::_get_uri();
	}
	
	
	
	/**
	* Defines the Request uri array property
	* 
	* @return array current request uri array
	*/
	
	public static function uri_array() {
		if (static::$_uri_array) return static::$_uri_array;
		if (static::$_uri) return static::$_uri_array = explode("/", static::$_uri);
		return static::$_uri_array = explode("/", static::_get_uri());
	}
	
	
	
	/**
	* Retreives a segent of the current uri array
	*/
	
	public static function segment($id) {
		if (!isset($_uri_array)) static::uri_array();
		return static::$_uri_array[$id];
	}
	
	
	
	/**
	* Returns the relative path of the root directory from the current uri
	*/
	
	public static function root_path() {
		if (!isset(static::$_uri_array)) static::uri_array();
		
		$total = count(static::uri_array()) - 1;		
		for ($i = 0; $i < $total; $i++) $path .= '../';
		return $path;
	}
	
	
	
	/**
	* Defines the Request id
	* 
	* @return int current request id
	*/
	
	public static function request_id() {
		if (static::$_request_id) return static::$_request_id;
		return static::_generate_request_id();
	}
	
	
		
	/**
	* Generates a request id for logging and debugging information
	* 
	* @return int request_id
	*/
	
	private static function _generate_request_id() {
		
		# insert trace into database
		$db = instance('core.controller.Database');
		$data = array(EXEC_START_TIME, static::uri());
		$query = $db->prepare("INSERT INTO fw5_request_log (time, uri) VALUES (?, ?);");
		$query->execute($data);
		
		# store and return the id of the exception log
		static::$_request_id = $db->lastInsertId();
		return static::$_request_id;
		
	}
	
	
	
	/**
	* Get's the current "pretty" URI from the URL.
	* It will also correct the QUERY_STRING server var and the $_GET array.
	* It supports all forms of mod_rewrite and the following forms of URL:
	* 
	* http://example.com/index.php/foo (returns '/foo')
	* http://example.com/index.php?/foo (returns '/foo')
	* http://example.com/index.php/foo?baz=bar (returns '/foo')
	* http://example.com/index.php?/foo?baz=bar (returns '/foo')
	* 
	* Similarly using mod_rewrite to remove index.php:
	* http://example.com/foo (returns '/foo')
	* http://example.com/foo?baz=bar (returns '/foo')
	* 
	* @author      Dan Horrigan <http://dhorrigan.com>
	* @copyright   Dan Horrigan
	* @license     MIT License <http://www.opensource.org/licenses/mit-license.php>
	* @return  string  the uri
	*/
	
	private static function _get_uri() {
		$prefix_slash = false;
		if (isset($_SERVER['PATH_INFO'])) {
			$uri = $_SERVER['PATH_INFO'];
		}
		elseif (isset($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
			
			if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
				$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
			}
			elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
				$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
			}
	
			// This section ensures that even on servers that require the URI to be in the query string a correct
			// URI is found, and also fixes the QUERY_STRING server var and $_GET array.
			if (strncmp($uri, '?/', 2) === 0) {
				$uri = substr($uri, 2);
			}
			
			$parts = preg_split('#\?#i', $uri, 2);
			$uri = $parts[0];
	
			if (isset($parts[1])) {
				$_SERVER['QUERY_STRING'] = $parts[1];
				parse_str($_SERVER['QUERY_STRING'], $_GET);
			}
			else {
				$_SERVER['QUERY_STRING'] = '';
				$_GET = array();
			}
			$uri = parse_url($uri, PHP_URL_PATH);
		}
		else {
			// Couldn't determine the URI, so just return false
			return false;
		}
		
		// Do some final cleaning of the URI and return it
		return ($prefix_slash ? '/' : '').str_replace(array('//', '../'), '/', ltrim($uri, '/'));
	}
}