<?php

namespace WDDSocial;

/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class ValidatorSQL{
	private $_info = array(
		
		'checkUserPassword' => "
			SELECT COUNT(*) AS `count`
			FROM users
			WHERE id = :id AND `password` = MD5(:password)",
		
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
		
		'checkUserVerification' => "
			SELECT verified
			FROM users
			WHERE id = :id",
		
		'checkUserExpiration' => "
			SELECT TIMESTAMPDIFF(DAY,`datetime`,NOW()) AS difference
			FROM users",
		
		'checkIfLinkExists' => "
			SELECT id
			FROM links
			WHERE title = :title AND link = :link
			LIMIT 1",
		
		'checkIfVideoExists' => "
			SELECT id
			FROM videos
			WHERE embedCode = :embedCode
			LIMIT 1",
		
		'isUserProjectOwner' => "
			SELECT p.id
			FROM projects AS p
			LEFT JOIN userProjects AS up ON (p.id = up.projectID)
			WHERE p.id = :projectID AND (p.userID = :userID OR up.userID = :userID)",
		
		'isUserArticleOwner' => "
			SELECT a.id
			FROM articles AS a
			LEFT JOIN userArticles AS ua ON (a.id = ua.articleID)
			WHERE a.id = :articleID AND (a.userID = :userID OR ua.userID = :userID)",
		
		'isUserEventOwner' => "
			SELECT id
			FROM events
			WHERE id = :eventID AND userID = :userID",
		
		'isUserJobOwner' => "
			SELECT id
			FROM jobs
			WHERE id = :jobID AND userID = :userID",
					
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
			WHERE jobID = :jobID AND linkID = :linkID",
		
		'checkIfProjectCourseExists' => "
			SELECT courseID
			FROM projectCourses
			WHERE projectID = :projectID AND courseID = :courseID",
		
		'checkIfArticleCourseExists' => "
			SELECT courseID
			FROM articleCourses
			WHERE articleID = :articleID AND courseID = :courseID",
		
		'checkIfEventCourseExists' => "
			SELECT courseID
			FROM eventCourses
			WHERE eventID = :eventID AND courseID = :courseID",
		
		'checkIfJobCourseExists' => "
			SELECT courseID
			FROM jobCourses
			WHERE jobID = :jobID AND courseID = :courseID"
	);
	
	public function __get($id){
		return $this->_info[$id];
	}
	
	public function __construct(){
		
	}
	
	public function __destruct(){
		
	}
}