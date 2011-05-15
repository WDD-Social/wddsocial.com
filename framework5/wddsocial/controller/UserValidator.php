<?php

namespace WDDSocial;

/*
* 
* 
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class UserValidator {
	
	/**
	* Checks if the current user is an owner of a project
	*/
	
	public static function is_project_owner($projectID){
		$db = instance(':db');
		$val = instance(':val-sql');
		
		if(UserSession::is_authorized()){
			$query = $db->prepare($val->isUserProjectOwner);
			$query->execute(array('userID' => UserSession::userid(), 'projectID' => $projectID));
			if ($query->rowCount() > 0)
				return true;
			else
				return false;
		}
		else {
			return false;
		}
	}
	
	
	
	/**
	* Checks if the current user is an owner of an article
	*/
	
	public static function is_article_owner($articleID){
		$db = instance(':db');
		$val = instance(':val-sql');
		
		if(UserSession::is_authorized()){
			$query = $db->prepare($val->isUserArticleOwner);
			$query->execute(array('userID' => UserSession::userid(), 'articleID' => $articleID));
			if ($query->rowCount() > 0)
				return true;
			else
				return false;
		}
		else {
			return false;
		}
	}
	
	
	
	/**
	* Checks if the current user is an owner of an event
	*/
	
	public static function is_event_owner($eventID){
		$db = instance(':db');
		$val = instance(':val-sql');
		
		if(UserSession::is_authorized()){
			$query = $db->prepare($val->isUserEventOwner);
			$query->execute(array('userID' => UserSession::userid(), 'eventID' => $eventID));
			if ($query->rowCount() > 0)
				return true;
			else
				return false;
		}
		else {
			return false;
		}
	}
	
	
	
	/**
	* Checks if the current user is an owner of a job
	*/
	
	public static function is_job_owner($job){
		$db = instance(':db');
		$val = instance(':val-sql');
		
		if(UserSession::is_authorized()){
			$query = $db->prepare($val->isUserJobOwner);
			$query->execute(array('userID' => UserSession::userid(), 'jobID' => $job));
			if ($query->rowCount() > 0)
				return true;
			else
				return false;
		}
		else {
			return false;
		}
	}
}