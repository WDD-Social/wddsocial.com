<?php

namespace WDDSocial;

class FormResponse {
	
	private $_info = array();
	
	
	
	public function __construct($status, $message = null) {
		# set values
		$this->_info['status'] = $status;
		if ($message)
			$this->_info['message'] = $message;
	}
	
	public function __get($id) {
		return $this->_info[$id];
	}
	
	public function __set($id, $value) {
		$this->_info[$id] = $value;
	}
}