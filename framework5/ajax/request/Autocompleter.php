<?php

namespace Ajax;

/**
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class Autocompleter implements \Framework5\IExecutable {
	
	public function execute() {
		
		# check user auth
		if (!\WDDSocial\UserSession::is_authorized()) return false;
		
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		
		switch ($_POST['type']) {
			case 'users':
				$response->results = $this->get_users($_POST['term']);
				$response->status = (count($response->results) > 0)?true:false;
				echo json_encode($response);
				break;
		}
	}
	
	private function get_users($term){
		$query = $this->db->prepare($this->sql->autocompleteUsers);
		$query->execute(array('term' => "$term%"));
		$query->setFetchMode(\PDO::FETCH_OBJ);
		return $query->fetchAll();
	}
}