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
	
	
	
	/**
	* Checks if the current user is an owner of a comment
	*/
	
	public static function is_comment_owner($comment){
		$db = instance(':db');
		$val = instance(':val-sql');
		
		if(UserSession::is_authorized()){
			$query = $db->prepare($val->isUserCommentOwner);
			$query->execute(array('userID' => UserSession::userid(), 'commentID' => $comment));
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
	* Checks if the current user is an owner of content
	*/
	
	public static function is_owner($id, $type){
		if(UserSession::is_authorized()){
			switch ($type) {
				case 'project':
					return static::is_project_owner($id);
					break;
				case 'article':
					return static::is_article_owner($id);
					break;
				case 'event':
					return static::is_event_owner($id);
					break;
				case 'job':
					return static::is_job_owner($id);
					break;
				case 'comment':
					return static::is_comment_owner($id);
					break;
				case 'user':
					return UserSession::is_current($id);
					break;
			}
		}
		else {
			return false;
		}
	}
	
	
	
	/**
	* Checks if the current user is the creator of content
	*/
	
	public static function is_creator($id, $type){
		$db = instance(':db');
		$sql = instance(':val-sql');
		
		if(UserSession::is_authorized()){
			$data = array('id' => $id, 'userID' => UserSession::userid());
			switch ($type) {
				case 'project':
					$query = $db->prepare($sql->isUserProjectCreator);
					break;
				case 'article':
					$query = $db->prepare($sql->isUserArticleCreator);
					break;
				case 'event':
					$query = $db->prepare($sql->isUserEventCreator);
					break;
				case 'job':
					$query = $db->prepare($sql->isUserJobCreator);
					break;
			}
			$query->execute($data);
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