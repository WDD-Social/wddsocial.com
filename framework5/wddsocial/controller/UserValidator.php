<?php

namespace WDDSocial;

/*
* 
* 
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class UserValidator {
	
	/**
	* Checks if a user is the currently signed in user
	*/
	
	public static function is_current($userID){
		if(static::is_authorized()){
			return ($userID == $_SESSION['user']->id)?true:false;
		}
	}
	
	
	
	/**
	* Checks if the current user is an owner of a project
	*/
	
	
	
	public static function is_project_owner($projectID){
		if(static::is_authorized()){
			$db = instance(':db');
			$sql = instance(':val-sql');
			$data = array('id' => $projectID);
			$query = $db->prepare($sql->getProjectOwners);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$query->execute($data);
			return static::if_current_is_in_array($query->fetchAll());
		}
	}
	
	
	
	/**
	* Checks if the current user is an owner of an article
	*/
	
	public static function is_article_owner($articleID){
		if(static::is_authorized()){
			$db = instance(':db');
			$sql = instance(':val-sql');
			$data = array('id' => $articleID);
			$query = $db->prepare($sql->getArticleOwners);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$query->execute($data);
			return static::if_current_is_in_array($query->fetchAll());
		}
	}
	
	
	
	/**
	* Checks if the current user is authorized
	*/
	
	public static function is_authorized(){
		return ($_SESSION['authorized'] == true)?true:false;
	}
	
	
	
	/**
	* Checks if the current user is authorized
	*/
	
	public static function if_current_is_in_array($array){
		$return = false;
		foreach($array as $item){
			if($item->userID == $_SESSION['user']->id){
				$return = true;
			}
		}
		return $return;
	}
}