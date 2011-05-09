<?php

namespace WDDSocial;

/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class AdminSQL{
	private $_info = array(
		
		/**
		* User queries
		*/
		
		'addUser' => "
			INSERT INTO users (typeID, firstName, lastName, email, fullsailEmail, `password`, vanityURL, bio, hometown, birthday, `datetime`)
			VALUES (:typeID, :firstName, :lastName, :email, :fullsailEmail, MD5(:password), :vanityURL, :bio, :hometown, :birthday, NOW())",
		
		'generateUserAvatar' => "
			UPDATE users
			SET avatar = MD5(CONCAT('user',id))
			WHERE id = :id",
		
		/**
		* Project Queries
		*/
		
		'addProject' => "
			INSERT INTO projects (userID, title, description, content, vanityURL, completeDate, `datetime`)
			VALUES (:userID, :title, :description, :content, :vanityURL, :completeDate, NOW())",
		
		'generateProjectVanityURL' => "
			UPDATE projects
			SET vanityURL = SUBSTRING(MD5(CONCAT('project',id)),1,6)
			WHERE id = :id",
		
		'addProjectTeamMember' => "
			INSERT INTO userProjects (userID, projectID, title)
			VALUES (:userID, :projectID, :title)",
		
		'addProjectCategory' => "
			INSERT INTO projectCategories (projectID, categoryID)
			VALUES (:projectID, :categoryID)",
		
		'addProjectCourse' => "
			INSERT INTO projectCourses (projectID, courseID)
			VALUES (:projectID, :courseID)",
		
		'addProjectLink' => "
			INSERT INTO projectLinks (projectID, linkID)
			VALUES (:projectID, :linkID)",
		
		'addProjectImage' => "
			INSERT INTO projectImages (projectID, imageID)
			VALUES (:projectID,:imageID)",
		
		'addProjectVideo' => "
			INSERT INTO projectVideos (projectID, videoID)
			VALUES (:projectID,:videoID)",
		
		/**
		* Article Queries
		*/
		
		'addArticle' => "
			INSERT INTO articles (userID, privacyLevelID, title, description, content, vanityURL, `datetime`)
			VALUES (:userID, :privacyLevelID, :title, :description, :content, :vanityURL, NOW())",
		
		'generateArticleVanityURL' => "
			UPDATE articles
			SET vanityURL = SUBSTRING(MD5(CONCAT('article',id)),1,6)
			WHERE id = :id",
		
		'addArticleAuthor' => "
			INSERT INTO userArticles (userID, articleID)
			VALUES (:userID, :articleID)",
		
		'addArticleCategory' => "
			INSERT INTO articleCategories (articleID, categoryID)
			VALUES (:articleID, :categoryID)",
		
		'addArticleCourse' => "
			INSERT INTO articleCourses (articleID, courseID)
			VALUES (:articleID, :courseID)",
		
		'addArticleLink' => "
			INSERT INTO articleLinks (articleID, linkID)
			VALUES (:articleID, :linkID)",
		
		'addArticleImage' => "
			INSERT INTO articleImages (articleID, imageID)
			VALUES (:articleID,:imageID)",
		
		'addArticleVideo' => "
			INSERT INTO articleVideos (articleID, videoID)
			VALUES (:articleID,:videoID)",
		
		/**
		* Event Queries
		*/
		
		'addEvent' => "
			INSERT INTO events (userID, privacyLevelID, title, description, content, vanityURL, location, startDatetime, endDatetime, `datetime`)
			VALUES (:userID, :privacyLevelID, :title, :description, :content, :vanityURL, :location, :startDatetime, DATE_ADD(:startDatetime, INTERVAL :duration HOUR), NOW());
			
			SET @last_id = LAST_INSERT_ID();
			
			UPDATE events
			SET icsUID = MD5(CONCAT(id,title,DATE_FORMAT(`datetime`, '%Y%m%dT%H%i%SZ'),'@wddsocial.com'))
			WHERE id = @last_id;",
		
		'generateEventVanityURL' => "
			UPDATE events
			SET vanityURL = SUBSTRING(MD5(CONCAT('event',id)),1,6)
			WHERE id = :id",
		
		'addEventCategory' => "
			INSERT INTO eventCategories (eventID, categoryID)
			VALUES (:eventID, :categoryID)",
		
		'addEventCourse' => "
			INSERT INTO eventCourses (eventID, courseID)
			VALUES (:eventID, :courseID)",
		
		'addEventLink' => "
			INSERT INTO eventLinks (eventID, linkID)
			VALUES (:eventID, :linkID)",
		
		'addEventImage' => "
			INSERT INTO eventImages (eventID, imageID)
			VALUES (:eventID,:imageID)",
		
		'addEventVideo' => "
			INSERT INTO eventVideos (eventID, videoID)
			VALUES (:eventID,:videoID)",
		
		/**
		* Job Queries
		*/
		
		'addJob' => "
			INSERT INTO jobs (userID, typeID, title, description, content, vanityURL, company, email, location, website, compensation, `datetime`)
			VALUES (:userID, :typeID, :title, :description, :content, :vanityURL, :company, :email, :location, :website, :compensation, NOW());
			
			SET @last_id = LAST_INSERT_ID();
			
			UPDATE jobs
			SET avatar = MD5(CONCAT('job',id))
			WHERE id = @last_id;",
		
		'generateJobVanityURL' => "
			UPDATE jobs
			SET vanityURL = SUBSTRING(MD5(CONCAT('job',id)),1,6)
			WHERE id = :id",
		
		'addJobCategory' => "
			INSERT INTO jobCategories (jobID, categoryID)
			VALUES (:jobID, :categoryID)",
		
		'addJobCourse' => "
			INSERT INTO jobCourses (jobID, courseID)
			VALUES (:jobID, :courseID)",
		
		'addJobLink' => "
			INSERT INTO jobLinks (jobID, linkID)
			VALUES (:jobID, :linkID)",
		
		'addJobImage' => "
			INSERT INTO jobImages (jobID, imageID)
			VALUES (:jobID,:imageID)",
		
		'addJobVideo' => "
			INSERT INTO jobVideos (jobID, videoID)
			VALUES (:jobID,:videoID)",
		
		/**
		* Category Queries
		*/
		
		'addCategory' => "
			INSERT INTO categories (title)
			VALUES (:title)",
		
		/**
		* Link Queries
		*/
		
		'addLink' => "
			INSERT INTO links (title, link)
			VALUES (:title, :link)",
		
		/**
		* Image Queries
		*/
		
		'addImage' => "
			INSERT INTO images (userID, title, `datetime`)
			VALUES (:userID, :title, NOW());
			
			SET @last_id = LAST_INSERT_ID();
			
			UPDATE images
			SET `file` = MD5(CONCAT('image',id))
			WHERE id = @last_id;",
		
		/**
		* Video Queries
		*/
		
		'addVideo' => "
			INSERT INTO videos (userID, embedCode, `datetime`)
			VALUES (:userID, :embedCode, NOW())"
		
	);
	
	public function __get($id){
		return $this->_info[$id];
	}
	
	public function __construct(){
		
	}
	
	public function __destruct(){
		
	}
}