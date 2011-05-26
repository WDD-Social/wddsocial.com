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
			case 'getEvents':
				echo $this->getEvents($_POST['start'],$_POST['limit'],$_POST['extra']['active']);
				break;
			case 'getSearchResults':
				echo $this->getSearchResults($_POST['extra']['term'],$_POST['extra']['type'],$_POST['start'],$_POST['limit'],$_POST['extra']['sort']);
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
				$query = (\WDDSocial\UserSession::is_authorized())?$this->db->prepare($this->sql->getArticles . " ORDER BY $orderBy LIMIT $start, $limit"):$this->db->prepare($this->sql->getPublicArticles . " ORDER BY $orderBy LIMIT $start, $limit");
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
	
	
	
	private function getEvents($start, $limit, $active){
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		switch ($active) {
			case 'upcoming':
				$orderBy = 'startDateTime ASC';
				break;
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
		$query = (\WDDSocial\UserSession::is_authorized())?$this->db->prepare($this->sql->getEvents . " ORDER BY $orderBy LIMIT $start, $limit"):$this->db->prepare($this->sql->getPublicEvents . " ORDER BY $orderBy LIMIT $start, $limit");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		# display section items
		while($item = $query->fetch()){
			$response .= render('wddsocial.view.content.WDDSocial\DirectoryItemView', array('type' => $item->type,'content' => $item));
		}
		return $response;
	}
	
	
	
	private function getSearchResults($term, $type, $start, $limit, $sort){
		
		switch ($sort) {
			case 'alphabetically':
				if ($type == 'people') {
					$orderBy = 'lastName ASC';
				}
				else {
					$orderBy = 'title ASC';
				}
				break;
			case 'newest':
				$orderBy = '`datetime` DESC';
				break;
			case 'oldest':
				$orderBy = '`datetime` ASC';
				break;
			case 'upcoming':
				$orderBy = 'startDateTime ASC';
				break;
			case 'month':
				$orderBy = '`month` ASC';
				break;
			case 'company':
				$orderBy = 'company ASC';
				break;
			case 'location':
				$orderBy = 'location ASC';
				break;
			default:
				if ($type == 'events') {
					$orderBy = 'title ASC';
				}
				else if ($type == 'courses') {
					$orderBy = '`month` ASC';
				}
				else {
					$orderBy = '`datetime` DESC';
				}
				break;
		}
		
		switch ($type) {
			case 'people':
				import('wddsocial.model.WDDSocial\UserVO');
				$query = $this->db->prepare($this->sql->searchPeople . " ORDER BY $orderBy" . " LIMIT $start, $limit");
				$query->execute(array('term' => "%$term%"));
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
				$results = $query->fetchAll();
				break;
			case 'projects':
				import('wddsocial.model.WDDSocial\DisplayVO');
				$query = $this->db->prepare($this->sql->searchProjects . " ORDER BY $orderBy" . " LIMIT $start, $limit");
				$query->execute(array('term' => "%$term%"));
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
				$results = $query->fetchAll();
				break;
			case 'articles':
				import('wddsocial.model.WDDSocial\DisplayVO');
				$query = (UserSession::is_authorized())?$this->db->prepare($this->sql->searchArticles . " ORDER BY $orderBy" . " LIMIT $start, $limit"):$this->db->prepare($this->sql->searchPublicArticles . " ORDER BY $orderBy" . " LIMIT $start, $limit");
				$query->execute(array('term' => "%$term%"));
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
				$results = $query->fetchAll();
				break;
			case 'courses':
				import('wddsocial.model.WDDSocial\CourseVO');
				$query = $this->db->prepare($this->sql->searchCourses . " ORDER BY $orderBy" . " LIMIT $start, $limit");
				$query->execute(array('term' => "%$term%"));
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\CourseVO');
				$results = $query->fetchAll();
				break;
			case 'events':
				import('wddsocial.model.WDDSocial\DisplayVO');
				$query = (UserSession::is_authorized())?$this->db->prepare($this->sql->searchEvents . " ORDER BY $orderBy" . " LIMIT $start, $limit"):$this->db->prepare($this->sql->searchPublicEvents . " ORDER BY $orderBy" . " LIMIT $start, $limit");
				$query->execute(array('term' => "%$term%"));
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
				$results = $query->fetchAll();
				break;
			case 'jobs':
				import('wddsocial.model.WDDSocial\JobVO');
				$query = $this->db->prepare($this->sql->searchJobs . " ORDER BY $orderBy" . " LIMIT $start, $limit");
				$query->execute(array('term' => "%$term%"));
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\JobVO');
				$results = $query->fetchAll();
				break;
			default:
				$results = array();
				break;
		}
		
		if (count($results) > 0) {
			switch ($type) {
				case 'people':
					foreach ($results as $person) {
						return render('wddsocial.view.content.WDDSocial\DirectoryUserItemView', array('content' => $person));
					}
					break;
				case 'projects':
					foreach ($results as $project) {
						return render('wddsocial.view.content.WDDSocial\DirectoryItemView', array('type' => 'project','content' => $project));
					}
					break;
				case 'articles':
					foreach ($results as $article) {
						return render('wddsocial.view.content.WDDSocial\DirectoryItemView', array('type' => 'article','content' => $article));
					}
					break;
				case 'courses':
					foreach ($results as $course) {
						return render('wddsocial.view.content.WDDSocial\DirectoryCourseItemView', $course);
					}
					break;
				case 'events':
					foreach ($results as $event) {
						return render('wddsocial.view.content.WDDSocial\DirectoryItemView', array('type' => 'event','content' => $event));
					}
					break;
				case 'jobs':
					foreach ($results as $event) {
						return render('wddsocial.view.content.WDDSocial\DirectoryItemView', array('type' => 'job','content' => $event));
					}
					break;
			}
		}
	}
}