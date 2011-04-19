<?php

namespace WDDSocial;
/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class DisplayVO{
	public $id, $title, $description, $vanityURL, $type, $date, $userID, $userFirstName, $userLastName, $userAvatar, $userURL, $team = array(), $images = array(), $comments, $tags = array();
	private $db, $sql;
	
	public function __construct(){
		$this->db = instance(':db');
		import('wddsocial.sql.SelectorSQL');
		$this->sql = new SelectorSQL();
		
		if($type != 'person'){
			$this->get_comments_count();
		}
		
		$this->get_tags();
		if($this->type == 'project' || $this->type == 'article'){
			$this->get_team();
			$this->get_images();
		}
	}
	
	private function get_comments_count(){
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
	
	private function get_tags(){
		$data = array('id' => $this->id);
		switch ($this->type){
			case 'project':
				$query = $this->db->prepare($this->sql->getProjectCategories);
				$query->execute($data);
				$all = array();
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($all,$row->title);
				}
				$rand = array_rand($all,2);
				foreach($rand as $tagKey){
					array_push($this->tags,$all[$tagKey]);
				}
				break;
			case 'article':
				$query = $this->db->prepare($this->sql->getArticleCategories);
				$query->execute($data);
				$all = array();
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($all,$row->title);
				}
				$rand = array_rand($all,2);
				foreach($rand as $tagKey){
					array_push($this->tags,$all[$tagKey]);
				}
				break;
			case 'event':
				$query = $this->db->prepare($this->sql->getEventCategories);
				$query->execute($data);
				$all = array();
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($all,$row->title);
				}
				$rand = array_rand($all,2);
				foreach($rand as $tagKey){
					array_push($this->tags,$all[$tagKey]);
				}
				break;
			case 'job':
				$query = $this->db->prepare($this->sql->getJobCategories);
				$query->execute($data);
				$all = array();
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($all,$row->title);
				}
				$rand = array_rand($all,2);
				foreach($rand as $tagKey){
					array_push($this->tags,$all[$tagKey]);
				}
				break;
		}
	}
	
	private function get_team(){
		import('wddsocial.model.UserVO');
		$data = array('id' => $this->id);
		switch ($this->type){
			case 'project':
				$query = $this->db->prepare($this->sql->getProjectTeam);
				$query->execute($data);
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
				while($user = $query->fetch()){
					array_push($this->team,$user);
				}
				break;
			case 'article':
				$query = $this->db->prepare($this->sql->getArticleTeam);
				$query->execute($data);
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
				while($user = $query->fetch()){
					array_push($this->team,$user);
				}
				break;
			default :
				break;
		}
	}
	
	private function get_images(){
		import('wddsocial.model.ImageVO');
		$data = array('id' => $this->id);
		switch ($this->type){
			case 'project':
				$query = $this->db->prepare($this->sql->getProjectImages);
				$query->execute($data);
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ImageVO');
				while($image = $query->fetch()){
					array_push($this->images,$image);
				}
				break;
			case 'article':
				$query = $this->db->prepare($this->sql->getArticleImages);
				$query->execute($data);
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ImageVO');
				while($image = $query->fetch()){
					array_push($this->images,$image);
				}
				break;
			default :
				break;
		}
	}
}