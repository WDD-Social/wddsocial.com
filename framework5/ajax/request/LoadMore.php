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
		
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		
		switch ($_POST['query']) {
			case 'getUserLatest':
				echo $this->getUserLatest($_POST['start'],$_POST['limit'],$_POST['extra']['userID']);
				break;
			case 'getPeople':
				echo $this->getPeople($_POST['start'],$_POST['limit'],$_POST['extra']['active']);
				break;
			case 'getProjects':
				echo $this->getProjectsArticles($_POST['start'],$_POST['limit'],$_POST['extra']['active'],'projects');
				break;
			case 'getArticles':
				echo $this->getProjectsArticles($_POST['start'],$_POST['limit'],$_POST['extra']['active'],'articles');
				break;
			default:
				echo $this->getLatest($_POST['start'],$_POST['limit']);
				break;
		}
	}
	
	
	
	private function getLatest($start, $limit){
		import('wddsocial.model.WDDSocial\DisplayVO');
		
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
		import('wddsocial.model.WDDSocial\DisplayVO');
		
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
	
	
	
	private function getPeople($start, $limit, $active){
		import('wddsocial.model.WDDSocial\UserVO');
		
		switch ($active) {
			case 'alphabetically':
				$orderBy = 'lastName ASC';
				break;
			case 'newest':
				$orderBy = '`datetime` DESC';
				break;
			case 'oldest':
				$orderBy = '`datetime` ASC';
				break;
			default:
				$orderBy = 'lastName ASC';
				break;
		}
		
		# query
		$query = $this->db->prepare($this->sql->getPeople . " ORDER BY $orderBy LIMIT $start, $limit");
		$query->execute(array('orderBy' => $orderBy));
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
		
		# display section items
		while($item = $query->fetch()){
			$response .= render('wddsocial.view.content.WDDSocial\DirectoryUserItemView', array('content' => $item));
		}
		return $response;
	}
	
	
	
	private function getProjectsArticles($start, $limit, $active, $type){
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		switch ($active) {
			case 'alphabetically':
				$orderBy = 'title ASC';
				break;
			case 'newest':
				$orderBy = '`datetime` DESC';
				break;
			case 'oldest':
				$orderBy = '`datetime` ASC';
				break;
			default:
				$orderBy = 'title ASC';
				break;
		}
		
		# query
		switch ($type) {
			case 'projects':
				$query = $this->db->prepare($this->sql->getProjects . " ORDER BY $orderBy LIMIT $start, $limit");
				break;
			case 'articles':
				$query = $this->db->prepare($this->sql->getArticles . " ORDER BY $orderBy LIMIT $start, $limit");
				break;
		}
		
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		# display section items
		while($item = $query->fetch()){
			$response .= render('wddsocial.view.content.WDDSocial\DirectoryItemView', array('type' => $item->type,'content' => $item));
		}
		return $response;
	}
}