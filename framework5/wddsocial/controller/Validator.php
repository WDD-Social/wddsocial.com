<?php

namespace WDDSocial;

/*
* 
* 
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class Validator {
	
	/**
	* Checks if a project has been flagged
	*/
	
	public static function project_has_been_flagged($id){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$query = $db->prepare($sql->getProjectFlagCount);
		$query->execute(array('id' => $id));
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		if ($result->flagCount >= 3) {
			return true;
		}
		else {
			return false;
		}
	}
	
	
	
	/**
	* Checks if an article has been flagged
	*/
	
	public static function article_has_been_flagged($id){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$query = $db->prepare($sql->getArticleFlagCount);
		$query->execute(array('id' => $id));
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		if ($result->flagCount >= 3) {
			return true;
		}
		else {
			return false;
		}
	}
	
	
	
	/**
	* Checks if an event has been flagged
	*/
	
	public static function event_has_been_flagged($id){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$query = $db->prepare($sql->getEventFlagCount);
		$query->execute(array('id' => $id));
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		if ($result->flagCount >= 3) {
			return true;
		}
		else {
			return false;
		}
	}
	
	
	
	/**
	* Checks if an event has expired
	*/
	
	public static function event_has_expired($id){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$query = $db->prepare($sql->hasEventExpired);
		$query->execute(array('id' => $id));
		$query->setFetchMode(\PDO::FETCH_OBJ);
		if ($query->rowCount() > 0) {
			return false;
		}
		else {
			return true;
		}
	}
	
	
	
	/**
	* Checks if a job has been flagged
	*/
	
	public static function job_has_been_flagged($id){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$query = $db->prepare($sql->getJobFlagCount);
		$query->execute(array('id' => $id));
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		if ($result->flagCount >= 3) {
			return true;
		}
		else {
			return false;
		}
	}
}