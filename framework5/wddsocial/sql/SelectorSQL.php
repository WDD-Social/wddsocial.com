<?php

namespace WDDSocial;

/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class SelectorSQL{
	private $_info = array(
		'getUserByID' => "
			SELECT u.id, ut.title AS `type`, languageID, firstName, lastName, email, fullsailEmail, avatar, vanityURL, bio, hometown, TIMESTAMPDIFF(YEAR,birthday,NOW()) AS age
			FROM users AS u
			LEFT JOIN userTypes AS ut ON (u.typeID = ut.id)
			WHERE u.id = :id",
			
		'getLatest' => "
			SELECT p.id, title, description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL,
			getDateDiffEN(p.datetime) AS `date`
			FROM projects AS p
			LEFT JOIN users AS u ON (p.userID = u.id)
			UNION
			SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, getDateDiffEN(a.datetime) AS `DATE`
			FROM articles AS a
			LEFT JOIN users AS u ON (a.userID = u.id)
			UNION
			SELECT id, CONCAT_WS(' ', firstName, lastName) AS title, bio AS description, vanityURL, `DATETIME`, 'person' AS `TYPE`, id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, vanityURL AS userURL, getDateDiffEN(`DATETIME`) AS `DATE`
			FROM users AS u
			ORDER BY DATETIME DESC
			LIMIT 0,10",
			
		'getLatest' => "
			SELECT p.id, title, description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, 
			IF(
			TIMESTAMPDIFF(MINUTE, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
			IF(
				TIMESTAMPDIFF(HOUR, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
				IF(
					TIMESTAMPDIFF(DAY, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 7,
					IF(
						TIMESTAMPDIFF(WEEK, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						DATE_FORMAT(p.datetime,'%M %D, %Y at %l:%i %p'),
						'Last week'
					),
					IF(
						TIMESTAMPDIFF(DAY, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(DAY, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'days ago'),
						'Yesterday'
					)
				),
				IF(
					TIMESTAMPDIFF(HOUR, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
					CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hours ago'),
					CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hour ago')
				)
			),
			IF(
				TIMESTAMPDIFF(MINUTE, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) = 0,
				'Just now',
				IF(
					TIMESTAMPDIFF(MINUTE, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
					CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minutes ago'),
					CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minute ago')
				)
			)
		) AS `date`
		FROM projects AS p
		LEFT JOIN users AS u ON (p.userID = u.id)
		UNION
		SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL,
		IF(
			TIMESTAMPDIFF(MINUTE, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
			IF(
				TIMESTAMPDIFF(HOUR, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
				IF(
					TIMESTAMPDIFF(DAY, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 7,
					IF(
						TIMESTAMPDIFF(WEEK, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
						'Last week'
					),
					IF(
						TIMESTAMPDIFF(DAY, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(DAY, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'days ago'),
						'Yesterday'
					)
				),
				IF(
					TIMESTAMPDIFF(HOUR, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
					CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hours ago'),
					CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hour ago')
				)
			),
			IF(
				TIMESTAMPDIFF(MINUTE, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) = 0,
				'Just now',
				IF(
					TIMESTAMPDIFF(MINUTE, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
					CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minutes ago'),
					CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minute ago')
				)
			)
		) AS `date`
		FROM articles AS a
		LEFT JOIN users AS u ON (a.userID = u.id)
		UNION
		SELECT id, CONCAT_WS(' ', firstName, lastName) AS title, bio AS description, vanityURL, `DATETIME`, 'person' AS `TYPE`, id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, vanityURL AS userURL,
		IF(
			TIMESTAMPDIFF(MINUTE, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
			IF(
				TIMESTAMPDIFF(HOUR, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
				IF(
					TIMESTAMPDIFF(DAY, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 7,
					IF(
						TIMESTAMPDIFF(WEEK, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						DATE_FORMAT(u.datetime,'%M %D, %Y at %l:%i %p'),
						'Last week'
					),
					IF(
						TIMESTAMPDIFF(DAY, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(DAY, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'days ago'),
						'Yesterday'
					)
				),
				IF(
					TIMESTAMPDIFF(HOUR, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
					CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hours ago'),
					CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hour ago')
				)
			),
			IF(
				TIMESTAMPDIFF(MINUTE, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) = 0,
				'Just now',
				IF(
					TIMESTAMPDIFF(MINUTE, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
					CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minutes ago'),
					CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minute ago')
				)
			)
		) AS `date`
		FROM users AS u
		ORDER BY DATETIME DESC
		LIMIT 0,10",
		
		'getUpcomingEvents' => "
			SELECT id, userID, icsUID, title, description, vanityURL, location, `datetime`, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`
			FROM events
			ORDER BY startDateTime ASC
			LIMIT 0,3",
			
		'getUpcomingPublicEvents' => "
			SELECT id, userID, icsUID, title, description, vanityURL, location, `datetime`, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`
			FROM events AS e
			LEFT JOIN privacyLevels AS p ON (e.privacyLevelID = p.id)
			WHERE p.title = 'Public'
			ORDER BY startDateTime ASC
			LIMIT 0,3",
			
		'getNewJobs' => "
			SELECT j.id, userID, j.title, company, jt.title AS jobType, avatar, location, compensation, description, website
			FROM jobs AS j
			LEFT JOIN jobTypes AS jt ON (j.typeID = jt.id)
			ORDER BY `datetime` DESC
			LIMIT 0,3",
			
		'getProjectCommentsCount' => "
			SELECT COUNT(*) as comments
			FROM projectComments
			WHERE projectID = :id",
			
		'getArticleCommentsCount' => "
			SELECT COUNT(*) as comments
			FROM articleComments
			WHERE articleID = :id",
			
		'getEventCommentsCount' => "
			SELECT COUNT(*) as comments
			FROM eventComments
			WHERE eventID = :id",
			
		'getProjectCategories' => "
			SELECT title
			FROM categories AS c
			LEFT JOIN projectCategories AS pc ON (c.id = pc.categoryID)
			WHERE pc.projectID = :id
			ORDER BY title",
			
		'getArticleCategories' => "
			SELECT title
			FROM categories AS c
			LEFT JOIN articleCategories AS ac ON (c.id = ac.categoryID)
			WHERE ac.articleID = :id
			ORDER BY title",
			
		'getEventCategories' => "
			SELECT title
			FROM categories AS c
			LEFT JOIN eventCategories AS ec ON (c.id = ec.categoryID)
			WHERE ec.eventID = :id
			ORDER BY title",
			
		'getJobCategories' => "
			SELECT title
			FROM categories AS c
			LEFT JOIN jobCategories AS jc ON (c.id = jc.categoryID)
			WHERE jc.jobID = :id
			ORDER BY title",
			
		'getProjectTeam' => "
			SELECT u.id, firstName, lastName, vanityURL
			FROM users AS u
			LEFT JOIN userProjects AS up ON (u.id = up.userID)
			WHERE up.projectID = :id
			ORDER BY lastName",
			
		'getArticleTeam' => "
			SELECT u.id, firstName, lastName vanityURL
			FROM users AS u
			LEFT JOIN userArticles AS ua ON (u.id = ua.userID)
			WHERE ua.articleID = :id
			ORDER BY lastName",
			
		'getProjectImages' => "
			SELECT id, title, description, `file`
			FROM images AS i
			LEFT JOIN projectImages AS pi ON(i.id = pi.imageID)
			WHERE pi.projectID = :id
			ORDER BY i.id ASC",
			
		'getArticleImages' => "
			SELECT id, title, description, `file`
			FROM images AS i
			LEFT JOIN articleImages AS ai ON(i.id = ai.imageID)
			WHERE ai.articleID = :id
			ORDER BY i.id ASC"
	);
	
	public function __get($id){
		return $this->_info[$id];
	}
	
	public function __construct(){
		
	}
	
	public function __destruct(){
		
	}
}