<?php

namespace Ajax;

/**
* Get news feed items
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class LoadMore implements \Framework5\IExecutable {
	
	public function execute() {
		
		# check user auth
		if (!\WDDSocial\UserSession::is_authorized()) redirect('/');
		
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		
		switch ($_POST['query']) {
			case 'user':
				echo $this->getUserLatest($_POST['start'],$_POST['limit'],$_POST['extra']['userID']);
				break;
			default:
				echo $this->getLatest($_POST['start'],$_POST['limit']);
				break;
		}
	}
	
	private function getLatest($start, $limit){
		if (!isset($start)) {
			$start = 0;
		}
		
		if (!isset($limit)) {
			$limit = 10;
		}
		
		$query = $this->db->prepare($this->sql->getLatest . " LIMIT $start, $limit");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		$response = '';
		while ($item = $query->fetch()) {
			$response .= render('wddsocial.view.content.WDDSocial\MediumDisplayView', array('type' => $item->type,'content' => $item));
		}
		return $response;
	}
	
	private function getUserLatest($start, $limit, $userID){
		if (!isset($start)) {
			$start = 0;
		}
		
		if (!isset($limit)) {
			$limit = 10;
		}
		
		$query = $this->db->prepare($this->sql->getUserLatest . " LIMIT $start, $limit");
		$query->execute(array('id' => $userID));
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		while ($item = $query->fetch()) {
			$response .= render('wddsocial.view.content.WDDSocial\MediumDisplayView', array('type' => $item->type,'content' => $item));
		}
		return $response;
	}
}