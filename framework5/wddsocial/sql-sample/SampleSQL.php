<?php

namespace WDDSocial;

/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class SelectorSQL{
	private $_info = array(
		'query_name' => "
			SELECT *
			FROM table_sample",
		'query2_name' => "
			SELECT *
			FROM other_table_sample"
	);
	
	public function __get($id){
		return $this->_info[$id];
	}
	
	public function __construct(){
		
	}
	
	public function __destruct(){
		
	}
}