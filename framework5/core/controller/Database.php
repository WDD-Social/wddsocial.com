<?php

namespace Framework5;

/*
* PDO Database controller
* @author tmatthews (tmatthewsdev@gmail.com)
*/

final class Database extends \PDO {
	
	public function __construct() {
				
		# call PDO construct
		parent::__construct("mysql:host=internal-db.s112587.gridserver.com;dbname=db112587_fw5debug", "db112587_social", "G*Uoj9F|S0i4f+tD");
		
		# set default attributes
		$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}
}