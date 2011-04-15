<?php

namespace WDDSocial;

/*
* Sample controller
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class Profile {
	
	
	# TMP @tmatthews
	public static function is_even($n) {
		return ($n & 1) ? false : true ;
	}
	
	//TODO remove namespace from AppException
	public function throwError() {
		throw new Exception('Number not even');
	}
	
	# TMP @tmatthews
	public function hello() {
		return 'hello world';
	}
	
	# TMP @tmatthews
	public function db() {
		$db = instance('core.controller.Database');
		
		$data = array('1', '1');
		$query = $db->prepare("INSERT INTO fw5_trace_log (time, memory) values (?);");
		$query->execute($data);
	}

	
}