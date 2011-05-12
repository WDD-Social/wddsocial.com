<?php

namespace WDDSocial;

/**
* 
* @author Anthony Colangelo (me@acolangelo.com) 
*/

class Paginator {
	public $page, $next, $last = false, $per, $limit;
	
	public function __construct($segment, $per, $max = null){
		$this->page = \Framework5\Request::segment($segment);
		if (!isset($this->page) or !is_numeric($this->page))
			$this->page = 1;
		
		if (isset($max) and $this->page >= $max) {
			$this->page = $max;
			$this->last = true;
		}
		
		$this->next = $this->page + 1;
			
		$this->per = $per;
		
		$this->limit = $this->page * $this->per;
	}
}