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
		$this->getCategories();
		$this->getLinks();
		if($this->type == 'project' || $this->type == 'article'){
			$this->getTeam();
		}
		if($this->type == 'project' || $this->type == 'article' || $this->type == 'event' || $this->type == 'job'){
			$this->getImages();
		}
		if($this->type == 'project' || $this->type == 'article' || $this->type == 'event' || $this->type == 'job'){
			$this->getVideos();
		}
		if($this->type == 'project' || $this->type == 'article' || $this->type == 'event'){
			$this->getComments();
		}
	}
	
	private function getCategories(){
		$data = array('id' => $this->id);
		switch ($this->type){
			case 'project':
				$query = $this->db->prepare($this->sql->getProjectCategories);
				$query->execute($data);
				while($category = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->categories,$category);
				}
				break;
			case 'article':
				$query = $this->db->prepare($this->sql->getArticleCategories);
				$query->execute($data);
				while($category = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->categories,$category);
				}
				break;
			case 'event':
				$query = $this->db->prepare($this->sql->getEventCategories);
				$query->execute($data);
				while($category = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->categories,$category);
				}
				break;
			case 'job':
				$query = $this->db->prepare($this->sql->getJobCategories);
				$query->execute($data);
				while($category = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->categories,$category);
				}
				break;
		}
	}
	
	private function getLinks(){
		$data = array('id' => $this->id);
		switch ($this->type){
			case 'project':
				$query = $this->db->prepare($this->sql->getProjectLinks);
				$query->execute($data);
				while($link = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->links,$link);
				}
				break;
			case 'article':
				$query = $this->db->prepare($this->sql->getArticleLinks);
				$query->execute($data);
				while($link = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->links,$link);
				}
				break;
			case 'event':
				$query = $this->db->prepare($this->sql->getEventLinks);
				$query->execute($data);
				while($link = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->links,$link);
				}
				break;
			case 'job':
				$query = $this->db->prepare($this->sql->getJobLinks);
				$query->execute($data);
				while($link = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->links,$link);
				}
				break;
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
			case 'event':
				$query = $this->db->prepare($this->sql->getEventVideos);
				$query->execute($data);
				while($video = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->videos,$video);
				}
				break;
			case 'job':
				$query = $this->db->prepare($this->sql->getJobVideos);
				$query->execute($data);
				while($video = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->videos,$video);
				}
				break;
		}
	}
	
	private function getComments(){
		$data = array('id' => $this->id);
		switch ($this->type){
			case 'project':
				$query = $this->db->prepare($this->sql->getProjectComments);
				$query->execute($data);
				while($comment = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->comments,$comment);
				}
				break;
			case 'article':
				$query = $this->db->prepare($this->sql->getArticleComments);
				$query->execute($data);
				while($comment = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->comments,$comment);
				}
				break;
			case 'event':
				$query = $this->db->prepare($this->sql->getEventComments);
				$query->execute($data);
				while($comment = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($this->comments,$comment);
				}
				break;
		}
	}
}