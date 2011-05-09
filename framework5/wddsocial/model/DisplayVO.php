<?php

namespace WDDSocial;
/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class DisplayVO{
	public $id, $title, $description, $vanityURL, $type, $date, $userID, $userFirstName, $userLastName, $userAvatar, $userURL, $team = array(), $images = array(), $categories = array(), $eventData, $comments;
	private $db, $sql;
	
	public function __construct(){
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		
		if($this->type == 'project' or $this->type == 'article' or $this->type == 'projectComment' or $this->type == 'articleComment' or $this->type == 'eventComment'){
			if ($this->type != 'eventComment')
				$this->get_team();

			$this->get_comments_count();
			$this->get_categories();
		}
		
		if($this->type == 'project' or $this->type == 'article')
			$this->get_images();
		
		if ($this->type == 'eventComment')
			$this->get_event_data();
	}
	
	
	
	/**
	* Gets comment count for content
	*/
	
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
			case 'projectComment':
				$query = $this->db->prepare($this->sql->getProjectCommentsCount);
				$query->execute($data);
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					$this->comments = $row->comments;
				}
				break;
			case 'articleComment':
				$query = $this->db->prepare($this->sql->getArticleCommentsCount);
				$query->execute($data);
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					$this->comments = $row->comments;
				}
				break;
			case 'eventComment':
				$query = $this->db->prepare($this->sql->getEventCommentsCount);
				$query->execute($data);
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					$this->comments = $row->comments;
				}
				break;
		}
	}
	
	
	
	/**
	* Gets categories for content
	*/
	
	private function get_categories(){
		$data = array('id' => $this->id);
		switch ($this->type){
			case 'project':
				$query = $this->db->prepare($this->sql->getProjectCategories);
				$query->execute($data);
				$all = array();
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($all,$row->title);
				}
				if(count($all) > 1){
					$rand = array_rand($all,2);
					foreach($rand as $categoryKey){
						array_push($this->categories,$all[$categoryKey]);
					}
				}else{
					foreach($all as $category){
						array_push($this->categories,$category);
					}
				}
				break;
			case 'article':
				$query = $this->db->prepare($this->sql->getArticleCategories);
				$query->execute($data);
				$all = array();
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($all,$row->title);
				}
				if(count($all) > 1){
					$rand = array_rand($all,2);
					foreach($rand as $categoryKey){
						array_push($this->categories,$all[$categoryKey]);
					}
				}else{
					foreach($all as $category){
						array_push($this->categories,$category);
					}
				}
				
				break;
			case 'job':
				$query = $this->db->prepare($this->sql->getJobCategories);
				$query->execute($data);
				$all = array();
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($all,$row->title);
				}
				if(count($all) > 1){
					$rand = array_rand($all,2);
					foreach($rand as $categoryKey){
						array_push($this->categories,$all[$categoryKey]);
					}
				}else{
					foreach($all as $category){
						array_push($this->categories,$category);
					}
				}
				break;
			case 'projectComment':
				$query = $this->db->prepare($this->sql->getProjectCategories);
				$query->execute($data);
				$all = array();
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($all,$row->title);
				}
				if(count($all) > 1){
					$rand = array_rand($all,2);
					foreach($rand as $categoryKey){
						array_push($this->categories,$all[$categoryKey]);
					}
				}else{
					foreach($all as $category){
						array_push($this->categories,$category);
					}
				}
				break;
			case 'articleComment':
				$query = $this->db->prepare($this->sql->getArticleCategories);
				$query->execute($data);
				$all = array();
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($all,$row->title);
				}
				if(count($all) > 1){
					$rand = array_rand($all,2);
					foreach($rand as $categoryKey){
						array_push($this->categories,$all[$categoryKey]);
					}
				}else{
					foreach($all as $category){
						array_push($this->categories,$category);
					}
				}
				
				break;
			case 'eventComment':
				$query = $this->db->prepare($this->sql->getEventCategories);
				$query->execute($data);
				$all = array();
				while($row = $query->fetch(\PDO::FETCH_OBJ)){
					array_push($all,$row->title);
				}
				if(count($all) > 1){
					$rand = array_rand($all,2);
					foreach($rand as $categoryKey){
						array_push($this->categories,$all[$categoryKey]);
					}
				}else{
					foreach($all as $category){
						array_push($this->categories,$category);
					}
				}
				
				break;
		}
	}
	
	
	
	/**
	* Gets team members for projects/articles
	*/
	
	private function get_team(){
		import('wddsocial.model.WDDSocial\UserVO');
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
			case 'projectComment':
				$query = $this->db->prepare($this->sql->getProjectTeam);
				$query->execute($data);
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
				while($user = $query->fetch()){
					array_push($this->team,$user);
				}
				break;
			case 'articleComment':
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
	
	
	
	/**
	* Gets images for projects/articles
	*/
	
	private function get_images(){
		import('wddsocial.model.WDDSocial\ImageVO');
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
	
	
	
	/**
	* Gets data for events
	*/
	
	private function get_event_data(){
		$data = array('id' => $this->id);
		$query = $this->db->prepare($this->sql->getEventCommentData);
		$query->execute($data);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$this->eventData = $query->fetch();
	}
}