<?php

namespace WDDSocial;

/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class ValidatorSQL{
	private $_info = array(
		
		'checkIfUserVanityURLExists' => "
			SELECT COUNT(*) AS `count`
			FROM users
			WHERE vanityURL = :vanityURL",
		
		'checkIfUserEmailExists' => "
			SELECT COUNT(*) AS `count`
			FROM users
			WHERE email = :email",
		
		'checkIfUserFullSailEmailExists' => "
			SELECT COUNT(*) AS `count`
			FROM users
			WHERE fullsailEmail = :fullsailEmail",
		
		'getProjectOwners' => "
			SELECT userID
			FROM userProjects
			WHERE projectID = :id",
		
		'getArticleOwners' => "
			SELECT userID
			FROM userArticles
			WHERE articleID = :id",
		
		'checkIfProjectCategoryExists' => "
			SELECT categoryID
			FROM projectCategories
			WHERE projectID = :projectID AND categoryID = :categoryID",
		
		'checkIfArticleCategoryExists' => "
			SELECT categoryID
			FROM articleCategories
			WHERE articleID = :articleID AND categoryID = :categoryID",
		
		'checkIfEventCategoryExists' => "
			SELECT categoryID
			FROM eventCategories
			WHERE eventID = :eventID AND categoryID = :categoryID",
		
		'checkIfJobCategoryExists' => "
			SELECT categoryID
			FROM jobCategories
			WHERE jobID = :jobID AND categoryID = :categoryID"
	);
	
	public function __get($id){
		return $this->_info[$id];
	}
	
	public function __construct(){
		
	}
	
	public function __destruct(){
		
	}
}