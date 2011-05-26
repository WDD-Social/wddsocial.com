<?php

namespace WDDSocial;
/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class JobVO{
	public $id, $userID, $title, $company, $description, $jobType, $avatar, $location, $compensation, $website, $categories = array(), $images = array();
	private $db, $sql;
	
	public function __construct(){
		import('wddsocial.sql.SelectorSQL');
		
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		
		$this->type = 'job';
		$this->get_categories();
		$this->get_images();
	}
	
	
	
	/**
	* Gets categories for job
	*/
	
	private function get_categories(){
		$data = array('id' => $this->id);
		$query = $this->db->prepare($this->sql->getJobCategories);
		$query->execute($data);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		while($row = $query->fetch()){
			array_push($this->categories,$row->title);
		}
	}
	
	
	
	/**
	* Gets images for job
	*/
	
	private function get_images(){
		$query = $this->db->prepare($this->sql->getJobImages);
		$query->execute(array('id' => $this->id));
		while($image = $query->fetch(\PDO::FETCH_OBJ)){
			array_push($this->images,$image);
		}
	}
}