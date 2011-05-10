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
		
		'checkIfLinkExists' => "
			SELECT id
			FROM links
			WHERE title = :title AND link = :link
			LIMIT 1",
		
		'getProjectOwners' => "
			SELECT userID
			FROM userProjects
			WHERE projectID = :id",
		
		'getArticleOwners' => "
			SELECT userID
			FROM userArticles
			WHERE articleID = :id",
		
		'checkIfProjectTeamMemberExists' => "
			SELECT userID
			FROM userProjects
			WHERE projectID = :projectID AND userID = :userID",
		
		'checkIfArticleAuthorExists' => "
			SELECT userID
			FROM userArticles
			WHERE articleID = :articleID AND userID = :userID",
		
		'checkIfProjectVanityURLExists' => "
			SELECT COUNT(*) AS `count`
			FROM projects
			WHERE vanityURL = :vanityURL",
		
		'checkIfArticleVanityURLExists' => "
			SELECT COUNT(*) AS `count`
			FROM articles
			WHERE vanityURL = :vanityURL",
		
		'checkIfEventVanityURLExists' => "
			SELECT COUNT(*) AS `count`
			FROM events
			WHERE vanityURL = :vanityURL",
		
		'checkIfJobVanityURLExists' => "
			SELECT COUNT(*) AS `count`
			FROM jobs
			WHERE vanityURL = :vanityURL",
		
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
			WHERE jobID = :jobID AND categoryID = :categoryID",
		
		'checkIfProjectLinkExists' => "
			SELECT linkID
			FROM projectLinks
			WHERE projectID = :projectID AND linkID = :linkID",
		
		'checkIfArticleLinkExists' => "
			SELECT linkID
			FROM articleLinks
			WHERE articleID = :articleID AND linkID = :linkID",
		
		'checkIfEventLinkExists' => "
			SELECT linkID
			FROM eventLinks
			WHERE eventID = :eventID AND linkID = :linkID",
		
		'checkIfJobLinkExists' => "
			SELECT linkID
			FROM jobLinks
			WHERE jobID = :jobID AND linkID = :linkID"
	);
	
	public function __get($id){
		return $this->_info[$id];
	}
	
	public function __construct(){
		
	}
	
	public function __destruct(){
		
	}
}