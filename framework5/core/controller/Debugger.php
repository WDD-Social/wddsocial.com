<?php

namespace Framework5;

/*
* Application debugger class
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Debugger extends Controller {

	private static $_debug_stack = array(); # holds DebugMessage()
	
	
	/**
	* Append a debug message to the stack
	* 
	* @param 
	* @param 
	* @return boolean
	*/
	public static function debug($message, $trace) {
		# get info passed from
		$info = array_shift($trace);
		
		# create a debug model and populate the data
		$debug = new DebugMessage();
		$debug->message = $message;
		$debug->file = array_pop(explode('/', $info['file']));
		$debug->path = $info['file'];
		$debug->line = $info['line'];
		$debug->time = number_format(microtime(true), 8, '.', '');
		$debug->memory = memory_get_usage();
		
		# add the DebugMessage to the stack
		array_push(static::$_debug_stack, $debug);
		return true;
	}
	
	
	/**
	* Returns the debug stack 
	* 
	* @return array(DebugMessage())
	*/
	
	public static function dump() {
		return static::$_debug_stack;
	}
}