<?php

/*
* PDO Database controller
* @author tmatthews (tmatthewsdev@gmail.com)
*/

final class Database extends \PDO {
	
	public function __construct() {
				
		# call PDO construct
		parent::__construct("mysql:host=localhost;dbname=sandbox", "root", "root");
		
		# set default attributes
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
}