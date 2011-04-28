<?php

namespace WDDSocial;
/*
* 
* 
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class ContentVO {
	public $id, $userID, $title, $type, $description, $content, $categories = array(), $links = array(), $team = array(), $images = array(), $videos = array(), $comments = array();
	
	private $db, $sql;
	
	public function __construct(){
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		if($this->type == 'project' || $this->type == 'article'){
			$this->getTeam();
		}
		if($this->type == 'project' || $this->type == 'article' || $this->type == 'event' || $this->type == 'job'){
			$this->getImages();
		}
		if($this->type == 'project' || $this->type == 'article' || $this->type == 'event' || $this->type == 'job'){
			$this->getVideos();
		}
	}
	
	private function getTeam(){
		$data = array('id' => $this->id);
		switch ($this->type){
			case 'project':
				$query = $this->db->prepare($this->sql->getProjectTeam);
				$query->execute($data);
				while($member = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->team,$member);
				}
				break;
			case 'article':
				$query = $this->db->prepare($this->sql->getArticleTeam);
				$query->execute($data);
				while($member = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->team,$member);
				}
				break;
		}
	}
	
	private function getImages(){
		$data = array('id' => $this->id);
		switch ($this->type){
			case 'project':
				$query = $this->db->prepare($this->sql->getProjectImages);
				$query->execute($data);
				while($image = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->images,$image);
				}
				break;
			case 'article':
				$query = $this->db->prepare($this->sql->getArticleImages);
				$query->execute($data);
				while($image = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->images,$image);
				}
				break;
			case 'event':
				$query = $this->db->prepare($this->sql->getEventImages);
				$query->execute($data);
				while($image = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->images,$image);
				}
				break;
			case 'job':
				$query = $this->db->prepare($this->sql->getJobImages);
				$query->execute($data);
				while($image = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->images,$image);
				}
				break;
		}
	}
	
	private function getVideos(){
		$data = array('id' => $this->id);
		switch ($this->type){
			case 'project':
				$query = $this->db->prepare($this->sql->getProjectVideos);
				$query->execute($data);
				while($video = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->videos,$video);
				}
				break;
			case 'article':
				$query = $this->db->prepare($this->sql->getArticleVideos);
				$query->execute($data);
				while($video = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->videos,$video);
				}
				break;
		}
	}
}