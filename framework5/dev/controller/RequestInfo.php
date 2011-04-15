<?php

/*
* Sample controller
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class RequestInfo {
	
	
	public function getRequests($limit = 0, $page = 0) {
		
		# get the db object
		$db = instance('core.controller.Database');
		
		$sql = "SELECT * FROM fw5_request_log ORDER BY id DESC";
		$query = $db->query($sql);
		
		# setting the fetch mode
		$query->setFetchMode(\PDO::FETCH_OBJ);
		
		return $query;
	}
	
	
	
	/**
	* 
	* 
	* @return dev.model.ExceptionInfo
	*/
	
	public function getException($request_id) {
		
		# get the db object
		$db = instance('core.controller.Database');
		
		# check if the an exception exists in the database
		$sql = "SELECT count(*) FROM fw5_exception_log WHERE request_id = $request_id";
		$query = $db->query($sql);
		$exception_caught = (bool)$query->fetchColumn();
		
		# if no exception was caught, return false
		if (!$exception_caught)
			return null;
		
		# if an exception was caught, return it
		$sql = "SELECT * FROM fw5_exception_log WHERE request_id = $request_id";
		$query = $db->query($sql);
		
		import('dev.model.request.ExceptionInfo');
		$query->setFetchMode(\PDO::FETCH_CLASS, 'ExceptionInfo');
		
		while($row = $query->fetch()) {
			return $row;
		}
	}
	
	
	
	/**
	* 
	* 
	*/
	
	public function getDebug($request_id) {
		import('dev.model.request.DebugInfo');
		
		$debug = new DebugInfo($request_id);
		
		if ($debug) return $debug;
		return null;
			
		
	}
}