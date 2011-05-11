<?php

namespace Framework5\Dev;

/*
* Sample controller
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class RequestInfo {
	
	private $_request_id;
	
	public function __construct($request_id) {
		$this->_request_id = $request_id;
		$this->db = instance('core.controller.Framework5\Database');
	}
	
	
	
	public function get_details() {
		
		import('dev.model.request.Framework5\Dev\ExecutionDetails');
		
		# Get db instance and query
		$sql = "SELECT * FROM fw5_request_log WHERE id = :id";
		$query = $this->db->prepare($sql);
		$query->setFetchMode(\PDO::FETCH_CLASS, 'Framework5\Dev\ExecutionDetails');
		$query->execute(array('id' => $this->_request_id));
		return $query->fetch();
	}
	
	
	public function get_execution() {
		
		import('dev.model.request.Framework5\Dev\ExecutionInfo');
		
		# Get db instance and query
		$sql = "SELECT * FROM fw5_execution_log WHERE request_id = :id";
		$query = $this->db->prepare($sql);
		$query->setFetchMode(\PDO::FETCH_CLASS, 'Framework5\Dev\ExecutionInfo');
		$query->execute(array('id' => $this->_request_id));
		return $query->fetch();
	}
	
	
	public function get_trace() {
		import('dev.model.request.Framework5\Dev\TraceInfo');
		
		# Get db instance and query
		$sql = "SELECT * FROM fw5_trace_log WHERE request_id = :id";
		$query = $this->db->prepare($sql);
		$query->setFetchMode(\PDO::FETCH_CLASS, 'Framework5\Dev\TraceInfo');
		$query->execute(array('id' => $this->_request_id));
		return $query->fetchAll();
	}
	
	
	public function get_exception() {
		
		import('dev.model.request.Framework5\Dev\ExceptionInfo');
		
		# Get db instance and query
		$sql = "SELECT * FROM fw5_exception_log WHERE request_id = :id";
		$query = $this->db->prepare($sql);
		$query->setFetchMode(\PDO::FETCH_CLASS, 'Framework5\Dev\ExceptionInfo');
		$query->execute(array('id' => $this->_request_id));
		return $query->fetch();
		
	}
	
	
	public function get_debug() {
		
		import('dev.model.request.Framework5\Dev\DebugInfo');
		
		# check if the an exception exists in the database
		$sql = "SELECT (data) FROM fw5_debug_log WHERE request_id = :id";
		$query = $this->db->prepare($sql);
		$query->setFetchMode(\PDO::FETCH_CLASS,'Framework5\Dev\DebugInfo');
		$query->execute(array('id' => $this->_request_id));
		return $query->fetch();
		
		
	}
}