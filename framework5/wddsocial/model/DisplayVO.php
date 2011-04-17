<?php

namespace WDDSocial;
/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class DisplayVO{
	public $id, $title, $description, $vanityURL, $type, $date, $userID, $userName, $userURL, $team = array(), $comments, $tags = array();
	private $db, $sql;
	
	public function __construct(){
		$this->db = instance(':db');
		import('wddsocial.sql.SelectorSQL');
		$this->sql = new SelectorSQL();
		
		if($type != 'person'){
			$this->getCommentsCount();
		}
		
		$this->getTags();
	}
	
	private function getCommentsCount(){
		$data = array('id' => $this->id);
		switch ($this->type){
			case 'project':
				$query = $this->db->prepare($this->sql->getProjectCommentsCount);
				$query->execute($data);
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					$this->comments = $row->comments;
				}
				break;
			case 'article':
				$query = $this->db->prepare($this->sql->getArticleCommentsCount);
				$query->execute($data);
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					$this->comments = $row->comments;
				}
				break;
			case 'event':
				$query = $this->db->prepare($this->sql->getEventCommentsCount);
				$query->execute($data);
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					$this->comments = $row->comments;
				}
				break;
			case 'job':
				$query = $this->db->prepare($this->sql->getJobCommentsCount);
				$query->execute($data);
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					$this->comments = $row->comments;
				}
				break;
		}
	}
	
	private function getTags(){
		$data = array('id' => $this->id);
		switch ($this->type){
			case 'project':
				$query = $this->db->prepare($this->sql->getProjectCategories);
				$query->execute($data);
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->tags,$row->title);
				}
				break;
			case 'article':
				$query = $this->db->prepare($this->sql->getArticleCategories);
				$query->execute($data);
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->tags,$row->title);
				}
				break;
			case 'event':
				$query = $this->db->prepare($this->sql->getEventCategories);
				$query->execute($data);
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->tags,$row->title);
				}
				break;
			case 'job':
				$query = $this->db->prepare($this->sql->getJobCategories);
				$query->execute($data);
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->tags,$row->title);
				}
				break;
		}
	}
}