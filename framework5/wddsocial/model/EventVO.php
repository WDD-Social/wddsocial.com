<?php

namespace WDDSocial;
/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class EventVO{
	public $id, $userID, $icsUID, $title, $description, $vanityURL, $location, $datetime, $type, $month, $day, $startTime, $endTime, $categories = array(), $comments;
	private $db, $sql;
	
	public function __construct(){
		
		import('wddsocial.sql.WDDSocial\SelectorSQL');
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		$this->type = 'event';
		$this->get_comments_count();
		$this->get_categories();
	}
	
	
	
	/**
	* Gets comment count for event
	*/
	
	private function get_comments_count(){
		$data = array('id' => $this->id);
		$query = $this->db->prepare($this->sql->getEventCommentsCount);
		$query->execute($data);
		while($row = $query->fetch(\PDO::FETCH_OBJ)){
			$this->comments = $row->comments;
		}
	}
	
	
	
	/**
	* Gets categories for event
	*/
	
	private function get_categories(){
		$data = array('id' => $this->id);
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
	}
}