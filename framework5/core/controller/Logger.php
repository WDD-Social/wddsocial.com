<?php

namespace Framework5;

/*
* Logs framework and application data to the database
*/

class Logger extends Controller {
	
	/**
	* Debug trace
	* 
	* @return trace id
	* @author tmatthews (tmatthewsdev@gmail.com)
	*/
	
	final public static function trace($message) {
		# prepare data to log
		$request_id = Request::request_id();
		$data = array($request_id, $message, time());
		
		# insert trace into database
		$db = instance('core.controller.Database');
		$query = $db->prepare("INSERT INTO fw5_trace_log (request_id, message, timestamp) VALUES (?, ?, ?);");
		$query->execute($data);
		
		# return the trace id
		return $db->lastInsertId();
	}
	
	
	
	/**
	* Logs an exception to the database
	* 
	* @author tmatthews (tmatthewsdev@gmail.com)
	* @param Exception
	* @return boolean
	*/
	
	final public static function log_exception($e) {
		
		debug('Logger: logging exception error');
		
		# prepare data to log
		$request_id = Request::request_id();
		$data = array(
			$request_id, get_class($e), time(), $e->getMessage(), $e->getCode(), 
			$e->getFile(), $e->getLine(), serialize($e->getTrace())
		);
		
		# insert trace into database
		$db = instance('core.controller.Database');
		$query = $db->prepare(
			"INSERT INTO fw5_exception_log (request_id, type, timestamp, message, code, file, line, trace) VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
		$query->execute($data);

		return true;
	}
	
	
	
	/**
	* Logs script execution time and memory usage in bytes
	* 
	* @return boolean
	*/
	
	final public static function log_execution() {
		
		debug('Logger: logging execution time');
		
		# prepare data to log
		$request_id = Request::request_id();
		$exec_time = microtime(true) - EXEC_START_TIME;
		$memory_peak = memory_get_peak_usage(true);
		
		$data = array($request_id, EXEC_START_TIME, $exec_time, $memory_peak);
		
		# insert trace into database
		$db = instance('core.controller.Database');
		$query = $db->prepare(
			"INSERT INTO fw5_execution_log (request_id, start_time, exec_time, memory_peak) VALUES (?, ?, ?, ?);");
		$query->execute($data);

		return true;
	}
	
	
	
	/**
	* Logs the debug stack to the database
	* 
	* @return boolean 
	*/
	
	final public static function log_debug($stack) {
		
		debug('Logger: logging execution time');
		
		# prepare data to log
		$request_id = Request::request_id();
		
		
		$data = array($request_id, serialize($stack));
		
		# insert trace into database
		$db = instance('core.controller.Database');
		$query = $db->prepare(
			"INSERT INTO fw5_debug_log (request_id, data) VALUES (?, ?);");
		$query->execute($data);

		return true;

	}
}