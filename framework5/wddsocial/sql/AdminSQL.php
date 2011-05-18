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
			INSERT INTO users (typeID, firstName, lastName, email, fullsailEmail, `password`, vanityURL, `datetime`)
			VALUES (:typeID, :firstName, :lastName, :email, :fullsailEmail, MD5(:password), :vanityURL, NOW());
			
			SET @last_id = LAST_INSERT_ID();
			
			UPDATE users
			SET avatar = MD5(CONCAT('user',id)), verificationCode = MD5(RAND())
			WHERE id = @last_id;
			
			INSERT INTO userDetail (userID)
			VALUES (@last_id);",
		
		'deleteUser' => "
			DELETE FROM users
			WHERE id = :id",
		
		'changePassword' => "
			UPDATE users
			SET `password` = MD5(:new)
			WHERE id = :id AND `password` = MD5(:old)",
		
		'verifyUserByID' => "
			UPDATE users
			SET verified = 1
			WHERE id = :id;
			
			UPDATE users
			SET verificationCode = NULL
			WHERE id = :id;",
		
		'updateUser' => "
			UPDATE users
			SET ",
		
		'updateUserDetail' => "
			UPDATE userDetail
			SET ",
		
		'deleteUserFromProjectTeams' => "
			DELETE FROM userProjects
			WHERE userID = :userID",
		
		'deleteUserFromArticleAuthors' => "
			DELETE FROM userArticles
			WHERE userID = :userID",
		
		'addTeacherCourse' => "
			INSERT INTO teacherCourses (userID, courseID)
			VALUES (:userID, :courseID)",
		
		'deleteTeacherCourse' => "
			DELETE FROM teacherCourses
			WHERE userID = :userID AND courseID = :courseID",
		
		'addUserLike' => "
			INSERT INTO userLikes (userID, categoryID)
			VALUES (:userID, :categoryID)",
		
		'deleteUserLike' => "
			DELETE FROM userLikes
			WHERE userID = :userID AND categoryID = :categoryID",
		
		'addUserDislike' => "
			INSERT INTO userDislikes (userID, categoryID)
			VALUES (:userID, :categoryID)",
		
		'deleteUserDislike' => "
			DELETE FROM userDislikes
			WHERE userID = :userID AND categoryID = :categoryID",
		
		/**
		* Project Queries
		*/
		
		'addProject' => "
			INSERT INTO projects (userID, title, description, content, vanityURL, completeDate, `datetime`)
			VALUES (:userID, :title, :description, :content, :vanityURL, :completeDate, NOW())",
		
		'deleteProject' => "
			DELETE FROM projects
			WHERE id = :id",
		
		'generateProjectVanityURL' => "
			UPDATE projects
			SET vanityURL = SUBSTRING(MD5(CONCAT(:extra,'project',id)),1,6)
			WHERE id = :id",
		
		'addProjectTeamMember' => "
			INSERT INTO userProjects (userID, projectID, title)
			VALUES (:userID, :projectID, :title)",
		
		'deleteProjectTeamMember' => "
			DELETE FROM userProjects
			WHERE userID = :userID AND projectID = :projectID",
		
		'updateProjectTeamMemberRole' => "
			UPDATE userProjects
			SET title = :title
			WHERE userID = :userID AND projectID = :projectID",
		
		'addProjectCategory' => "
			INSERT INTO projectCategories (projectID, categoryID)
			VALUES (:projectID, :categoryID)",
		
		'deleteProjectCategory' => "
			DELETE FROM projectCategories
			WHERE projectID = :projectID AND categoryID = :categoryID",
		
		'addProjectCourse' => "
			INSERT INTO projectCourses (projectID, courseID)
			VALUES (:projectID, :courseID)",
		
		'deleteProjectCourse' => "
			DELETE FROM projectCourses
			WHERE projectID = :projectID AND courseID = :courseID",
		
		'addProjectLink' => "
			INSERT INTO projectLinks (projectID, linkID)
			VALUES (:projectID, :linkID)",
		
		'deleteProjectLink' => "
			DELETE FROM projectLinks
			WHERE projectID = :projectID AND linkID = :linkID",
		
		'addProjectImage' => "
			INSERT INTO projectImages (projectID, imageID)
			VALUES (:projectID,:imageID)",
		
		'addProjectVideo' => "
			INSERT INTO projectVideos (projectID, videoID)
			VALUES (:projectID,:videoID)",
		
		'deleteProjectVideo' => "
			DELETE FROM projectVideos
			WHERE projectID = :projectID AND videoID = :videoID",
		
		'addProjectComment' => "
			INSERT INTO projectComments (projectID, commentID)
			VALUES (:projectID,:commentID)",
		
		'updateProject' => "
			UPDATE projects
			SET ",
		
		'changeProjectOwner' => "
			UPDATE projects AS p
			SET userID = (SELECT up.userID
				FROM userProjects AS up
				WHERE up.projectID = :projectID
				GROUP BY p.id)
			WHERE p.id = :projectID",
		
		/**
		* Article Queries
		*/
		
		'addArticle' => "
			INSERT INTO articles (userID, privacyLevelID, title, description, content, vanityURL, `datetime`)
			VALUES (:userID, :privacyLevelID, :title, :description, :content, :vanityURL, NOW())",
		
		'deleteArticle' => "
			DELETE FROM articles
			WHERE id = :id",
		
		'generateArticleVanityURL' => "
			UPDATE articles
			SET vanityURL = SUBSTRING(MD5(CONCAT(:extra,'article',id)),1,6)
			WHERE id = :id",
		
		'addArticleAuthor' => "
			INSERT INTO userArticles (userID, articleID)
			VALUES (:userID, :articleID)",
		
		'deleteArticleAuthor' => "
			DELETE FROM userArticles
			WHERE userID = :userID AND articleID = :articleID",
		
		'addArticleCategory' => "
			INSERT INTO articleCategories (articleID, categoryID)
			VALUES (:articleID, :categoryID)",
		
		'deleteArticleCategory' => "
			DELETE FROM articleCategories
			WHERE articleID = :articleID AND categoryID = :categoryID",
		
		'addArticleCourse' => "
			INSERT INTO articleCourses (articleID, courseID)
			VALUES (:articleID, :courseID)",
		
		'deleteArticleCourse' => "
			DELETE FROM articleCourses
			WHERE articleID = :articleID AND courseID = :courseID",
		
		'addArticleLink' => "
			INSERT INTO articleLinks (articleID, linkID)
			VALUES (:articleID, :linkID)",
		
		'deleteArticleLink' => "
			DELETE FROM articleLinks
			WHERE articleID = :articleID AND linkID = :linkID",
		
		'addArticleImage' => "
			INSERT INTO articleImages (articleID, imageID)
			VALUES (:articleID,:imageID)",
		
		'addArticleVideo' => "
			INSERT INTO articleVideos (articleID, videoID)
			VALUES (:articleID,:videoID)",
		
		'deleteArticleVideo' => "
			DELETE FROM articleVideos
			WHERE articleID = :articleID AND videoID = :videoID",
		
		'addArticleComment' => "
			INSERT INTO articleComments (articleID, commentID)
			VALUES (:articleID,:commentID)",
		
		'updateArticle' => "
			UPDATE articles
			SET ",
		
		'changeArticleOwner' => "
			UPDATE articles AS a
			SET userID = (SELECT ua.userID
				FROM userArticles AS ua
				WHERE ua.articleID = :articleID
				GROUP BY a.id)
			WHERE a.id = :articleID",
		
		/**
		* Event Queries
		*/
		
		'addEvent' => "
			INSERT INTO events (userID, privacyLevelID, title, description, content, vanityURL, location, startDatetime, endDatetime, `datetime`)
			VALUES (:userID, :privacyLevelID, :title, :description, :content, :vanityURL, :location, :startDatetime, DATE_ADD(:startDatetime, INTERVAL :duration HOUR), NOW())",
		
		'deleteEvent' => "
			DELETE FROM events
			WHERE id = :id",
		
		'generateEventICSUID' => "
			UPDATE events
			SET icsUID = MD5(CONCAT(id,title,DATE_FORMAT(`datetime`, '%Y%m%dT%H%i%SZ'),'@wddsocial.com'))
			WHERE id = :id",
		
		'generateEventVanityURL' => "
			UPDATE events
			SET vanityURL = SUBSTRING(MD5(CONCAT(:extra,'event',id)),1,6)
			WHERE id = :id",
		
		'addEventCategory' => "
			INSERT INTO eventCategories (eventID, categoryID)
			VALUES (:eventID, :categoryID)",
		
		'deleteEventCategory' => "
			DELETE FROM eventCategories
			WHERE eventID = :eventID AND categoryID = :categoryID",
		
		'addEventCourse' => "
			INSERT INTO eventCourses (eventID, courseID)
			VALUES (:eventID, :courseID)",
		
		'deleteEventCourse' => "
			DELETE FROM eventCourses
			WHERE eventID = :eventID AND courseID = :courseID",
		
		'addEventLink' => "
			INSERT INTO eventLinks (eventID, linkID)
			VALUES (:eventID, :linkID)",
		
		'deleteEventLink' => "
			DELETE FROM eventLinks
			WHERE eventID = :eventID AND linkID = :linkID",
		
		'addEventImage' => "
			INSERT INTO eventImages (eventID, imageID)
			VALUES (:eventID,:imageID)",
		
		'addEventVideo' => "
			INSERT INTO eventVideos (eventID, videoID)
			VALUES (:eventID,:videoID)",
		
		'deleteEventVideo' => "
			DELETE FROM eventVideos
			WHERE eventID = :eventID AND videoID = :videoID",
		
		'addEventComment' => "
			INSERT INTO eventComments (eventID, commentID)
			VALUES (:eventID,:commentID)",
		
		'updateEvent' => "
			UPDATE events
			SET ",
		
		/**
		* Job Queries
		*/
		
		'addJob' => "
			INSERT INTO jobs (userID, typeID, title, description, content, vanityURL, company, email, location, website, compensation, `datetime`)
			VALUES (:userID, :typeID, :title, :description, :content, :vanityURL, :company, :email, :location, :website, :compensation, NOW())",
		
		'deleteJob' => "
			DELETE FROM jobs
			WHERE id = :id",
		
		'generateJobVanityURL' => "
			UPDATE jobs
			SET vanityURL = SUBSTRING(MD5(CONCAT(:extra,'job',id)),1,6)
			WHERE id = :id",
		
		'generateJobAvatar' => "
			UPDATE jobs
			SET avatar = MD5(CONCAT('job',id))
			WHERE id = :id",
		
		'addJobCategory' => "
			INSERT INTO jobCategories (jobID, categoryID)
			VALUES (:jobID, :categoryID)",
		
		'deleteJobCategory' => "
			DELETE FROM jobCategories
			WHERE jobID = :jobID AND categoryID = :categoryID",
		
		'addJobCourse' => "
			INSERT INTO jobCourses (jobID, courseID)
			VALUES (:jobID, :courseID)",
		
		'deleteJobCourse' => "
			DELETE FROM jobCourses
			WHERE jobID = :jobID AND courseID = :courseID",
		
		'addJobLink' => "
			INSERT INTO jobLinks (jobID, linkID)
			VALUES (:jobID, :linkID)",
		
		'deleteJobLink' => "
			DELETE FROM jobLinks
			WHERE jobID = :jobID AND linkID = :linkID",
		
		'addJobImage' => "
			INSERT INTO jobImages (jobID, imageID)
			VALUES (:jobID,:imageID)",
		
		'addJobVideo' => "
			INSERT INTO jobVideos (jobID, videoID)
			VALUES (:jobID,:videoID)",
		
		'deleteJobVideo' => "
			DELETE FROM jobVideos
			WHERE jobID = :jobID AND videoID = :videoID",
		
		'updateJob' => "
			UPDATE jobs
			SET ",
		
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
		
		'updateImage' => "
			UPDATE images
			SET title = :title
			WHERE id = :id",
		
		'deleteImage' => "
			DELETE FROM images
			WHERE file = :file",
		
		/**
		* Video Queries
		*/
		
		'addVideo' => "
			INSERT INTO videos (userID, embedCode, `datetime`)
			VALUES (:userID, :embedCode, NOW())",
		
		/**
		* Comment Queries
		*/
		
		'addComment' => "
			INSERT INTO comments (userID, content, `datetime`)
			VALUES (:userID, :content, NOW())",
		
		'updateComment' => "
			UPDATE comments
			SET content = :content
			WHERE id = :id",
		
		'deleteComment' => "
			DELETE FROM comments
			WHERE id = :id",
		
		/**
		* Flag Queries
		*/
		
		'flagProject' => "
			INSERT INTO projectFlags (projectID, userID, `datetime`)
			VALUES (:id, :userID, NOW())",
		
		'flagArticle' => "
			INSERT INTO articleFlags (articleID, userID, `datetime`)
			VALUES (:id, :userID, NOW())",
		
		'flagEvent' => "
			INSERT INTO eventFlags (eventID, userID, `datetime`)
			VALUES (:id, :userID, NOW())",
		
		'flagJob' => "
			INSERT INTO jobFlags (jobID, userID, `datetime`)
			VALUES (:id, :userID, NOW())",
		
		'flagComment' => "
			INSERT INTO commentFlags (commentID, userID, `datetime`)
			VALUES (:id, :userID, NOW())",
		
		'unflagProject' => "
			DELETE FROM projectFlags
			WHERE projectID = :id AND userID = :userID",
		
		'unflagArticle' => "
			DELETE FROM articleFlags
			WHERE articleID = :id AND userID = :userID",
		
		'unflagEvent' => "
			DELETE FROM eventFlags
			WHERE eventID = :id AND userID = :userID",
		
		'unflagJob' => "
			DELETE FROM jobFlags
			WHERE jobID = :id AND userID = :userID",
		
		'unflagComment' => "
			DELETE FROM commentFlags
			WHERE commentID = :id AND userID = :userID"
		
	);
	
	public function __get($id){
		return $this->_info[$id];
	}
	
	public function __construct(){
		
	}
	
	public function __destruct(){
		
	}
}