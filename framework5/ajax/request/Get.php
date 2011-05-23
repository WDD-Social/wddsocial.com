<?php

namespace Ajax;

/**
* Gets basic data
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class Get implements \Framework5\IExecutable {
	
	public function execute() {
		
		# check user auth
		if (!\WDDSocial\UserSession::is_authorized()) return false;
		
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		
		switch ($_POST['query']) {
			case 'getUserIDByVanityURL':
				echo $this->getUserIDByVanityURL($_POST['vanityURL']);
				break;
		}
	}
	
	private function getUserIDByVanityURL($vanityURL){
		$query = $this->db->prepare($this->sql->getUserIDByVanityURL);
		$query->execute(array('vanityURL' => $_POST['vanityURL']));
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		return $result->id;
	}
}