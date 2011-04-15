<?php

/*
* 
* 
*/

class DebugInfo {
	
	public $request_id, $data;
	
	public function __construct($request_id) {
		
		# get the db object
		$db = instance('core.controller.Database');
		
		# check if the an exception exists in the database
		$sql = "SELECT count(*) FROM fw5_debug_log WHERE request_id = $request_id";
		$query = $db->query($sql);
		$debug_data = (bool)$query->fetchColumn();
		
		# if no exception was caught, return false
		if (!$debug_data)
			return null;
		
		# if debug info was found, return it
		$sql = "SELECT (request_id, data) FROM fw5_debug_log WHERE request_id = $request_id";
		$query = $db->query($sql);
		
		
		$query->setFetchMode(PDO::FETCH_INTO);
		$data = $query->fetch();
		
		foreach ($data as $name => $value)
			$this->{$name} = $value;
		
		return true;
	}
}