<?php

namespace WDDSocial;
/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class JobVO{
	public $id, $userID, $title, $company, $description, $jobType, $avatar, $location, $compensation, $website, $categories = array();
	private $db, $sql;
	
	public function __construct(){
		import('wddsocial.sql.SelectorSQL');
		
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		
		$this->type = 'job';
		$this->get_categories();
	}
	
	
	
	/**
	* Gets categories for job
	*/
	
	private function get_categories(){
		$data = array('id' => $this->id);
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
	}
}