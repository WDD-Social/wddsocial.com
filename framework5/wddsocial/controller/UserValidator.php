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
		return ($userID == $_SESSION['user']->id)?true:false;
	}
	
	/**
	* Checks if the current user is an owner of a project
	*/
	
	public static function is_project_owner($projectID){
		//return ($userID == $_SESSION['user']->id)?true:false;
	}
	
	
	
	/**
	* Checks if the current user is authorized
	*/
	
	public static function is_authorized(){
		return ($_SESSION['authorized'] == true)?true:false;
	}
}