<?php

namespace WDDSocial;
/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class EventVO{
	public $id, $userID, $icsUID, $title, $description, $vanityURL, $location, $datetime, $type, $month, $day, $startTime, $endTime, $tags = array(), $comments;
	private $db, $sql;
	
	public function __construct(){
		$this->db = instance(':db');
		import('wddsocial.sql.SelectorSQL');
		$this->sql = new SelectorSQL();
		
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
		$rand = array_rand($all,2);
		foreach($rand as $tagKey){
			array_push($this->tags,$all[$tagKey]);
		}
	}
}