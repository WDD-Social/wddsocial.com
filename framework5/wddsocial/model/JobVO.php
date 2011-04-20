<?php

namespace WDDSocial;
/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class JobVO{
	public $id, $userID, $title, $company, $description, $jobType, $avatar, $location, $compensation, $website, $tags = array();
	private $db, $sql;
	
	public function __construct(){
		$this->db = instance(':db');
		import('wddsocial.sql.SelectorSQL');
		$this->sql = new SelectorSQL();
		
		$this->type = 'job';
		$this->get_tags();
	}
	
	private function get_tags(){
		$data = array('id' => $this->id);
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
	}
}