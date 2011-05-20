<?php

namespace Framework5\Dev;

/**
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class DatabasePage implements \Framework5\IExecutable {
	
	public function execute() {
		
		# connect to the application database
		$this->db_connect();
		
		$content = $this->page_action();
		
		
		
		# display page
		echo render(':template', array('title' => 'Database', 'content' => $content));
	}
	
	
	
	private function db_connect() {
		# site db connection
		$this->db = new \PDO("mysql:host=internal-db.s112587.gridserver.com;dbname=db112587_wddsocial", "db112587_social", "G*Uoj9F|S0i4f+tD");
		$this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}
	
	
	
	private function page_action() {
		
		$action = \Framework5\Request::segment(2);
		switch ($action) {
			
			# view table info
			case 'table':
				$table = \Framework5\Request::segment(3);
				if ($table) {
					$table_desc = $this->describe_table($table);
					if ($table_desc) {
						return render('dev.view.database.Framework5\Dev\TableDescriptionView', $table_desc);
					}
				}
				else {
					return 'no table';
				}
			
			# render list of database tables
			default:
				$tables = $this->get_tables();
				if ($tables) return render('dev.view.database.Framework5\Dev\TablesView', $tables);
				else return "no tables to display";
		}
	}
	
	
	
	/**
	* Gets a list of all database tables
	*/
	
	private function get_tables() {
		$sql = "
			SHOW TABLES;
		";
		
		$query = $this->db->query($sql);
		$query->setFetchMode(\PDO::FETCH_ASSOC);
		$tables = $query->fetchAll();
		
		# format the data
		$table_array = array();
		foreach($tables as $table) {
			foreach($table as $key => $value) {
				array_push($table_array, $value);
			}	
		}
		
		return $table_array;
	}
	
	
	
	/**
	* Determines if given is a valid table name
	*/
	
	private function valid_table($table) {}
	
	
	
	/**
	* 
	*/
	
	private function describe_table($table) {
		
		$sql = "
			DESCRIBE $table;
		";
		
		$query = $this->db->query($sql);
		$query->setFetchMode(\PDO::FETCH_ASSOC);
		//$query->execute(array('table' => $table));
		$table_info = $query->fetchAll();
		
		# format the data
		/*$table_array = array();
		foreach($tables as $table) {
			foreach($table as $key => $value) {
				array_push($table_array, $value);
			}	
		}*/
		
		return $table_info;
	}
	
	
}