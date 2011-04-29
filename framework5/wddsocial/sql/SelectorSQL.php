<?php

namespace WDDSocial;

/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class SelectorSQL{
	private $_info = array(
		
		/**
		* Creates getDateDiff function
		*/
		
		'createDateTimeFunc' => '
			DELIMITER //

			CREATE FUNCTION getDateDiffEN(contentDate DATETIME)
				RETURNS VARCHAR(64)
				
				BEGIN
					IF TIMESTAMPDIFF(MINUTE, contentDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59
						THEN 
							IF TIMESTAMPDIFF(HOUR, contentDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23
								THEN
									IF TIMESTAMPDIFF(DAY, contentDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14
										THEN RETURN DATE_FORMAT(contentDate,"%M %D, %Y at %l:%i %p");
									ELSEIF TIMESTAMPDIFF(DAY, contentDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1
										THEN RETURN CONCAT_WS(" ", TIMESTAMPDIFF(DAY, contentDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)), "days ago");
									ELSE RETURN "Yesterday";
									END IF;
							ELSEIF TIMESTAMPDIFF(HOUR, contentDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1
								THEN RETURN CONCAT_WS(" ", TIMESTAMPDIFF(HOUR, contentDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)), "hours ago");
							ELSE RETURN CONCAT_WS(" ", TIMESTAMPDIFF(HOUR, contentDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)), "hour ago");
							END IF;
					ELSEIF TIMESTAMPDIFF(MINUTE, contentDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) = 0
						THEN RETURN "Just now";
					ELSEIF TIMESTAMPDIFF(MINUTE, contentDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1
						THEN RETURN CONCAT_WS(" ", TIMESTAMPDIFF(MINUTE, contentDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)), "minutes ago");
					ELSE RETURN CONCAT_WS(" ", TIMESTAMPDIFF(MINUTE, contentDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)), "minute ago");
					END IF;
				END //
			
			DELIMITER ;',
		
		/**
		* Activity feed queries
		*/
			
		'getLatest' => "
			SELECT p.id, title, description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, 
			IF(
				TIMESTAMPDIFF(MINUTE, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(p.datetime,'%M %D, %Y at %l:%i %p'),
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
						TIMESTAMPDIFF(DAY, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
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
						TIMESTAMPDIFF(DAY, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(u.datetime,'%M %D, %Y at %l:%i %p'),
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
			LIMIT 0,20",
		
		'getUserLatest' => "
			SELECT p.id, p.title, description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, 
			IF(
				TIMESTAMPDIFF(MINUTE, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(p.datetime,'%M %D, %Y at %l:%i %p'),
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
			LEFT JOIN userProjects AS up ON (p.id = up.projectID)
			WHERE u.id = :id OR up.userID = :id
			UNION
			SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL,
			IF(
				TIMESTAMPDIFF(MINUTE, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
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
			LEFT JOIN userArticles AS ua ON (a.id = ua.articleID)
			WHERE u.id = :id OR ua.userID = :id
			ORDER BY DATETIME DESC
			LIMIT 0,20
			",
			
			
		/**
		* People queries
		*/
		
		'getUserByID' => "
			SELECT u.id, firstName, lastName, avatar, vanityURL, bio, hometown, TIMESTAMPDIFF(YEAR, birthday, DATE_ADD(NOW(), INTERVAL 3 HOUR)) AS age, ut.title AS `type`, website, twitter, facebook, github, dribbble, forrst
			FROM users AS u
			LEFT JOIN userTypes AS ut ON (u.typeID = ut.id)
			WHERE u.id = :id",
		
		'getUserByVanityURL' => "
			SELECT u.id, firstName, lastName, avatar, vanityURL, bio, hometown, TIMESTAMPDIFF(YEAR, birthday, DATE_ADD(NOW(), INTERVAL 3 HOUR)) AS age, ut.title AS `type`, website, twitter, facebook, github, dribbble, forrst
			FROM users AS u
			LEFT JOIN userTypes AS ut ON (u.typeID = ut.id)
			WHERE vanityURL = :vanityURL",
		
		'getStudentDetailByID' => "
			SELECT DATE_FORMAT(startDate,'%M, %Y') AS startDate, location
			FROM studentDetail
			WHERE userID = :id",
		
		'getTeacherCoursesByID' => "
			SELECT id, title, `month`
			FROM teacherCourses AS tc
			LEFT JOIN courses AS c ON (c.id = tc.courseID)
			WHERE userID = :id",
		
		'getAlumDetailByID' => "
			SELECT DATE_FORMAT(graduationDate,'%M, %Y') AS graduationDate, employerTitle, employerLink
			FROM alumDetail
			WHERE userID = :id",
		
		'getUserLikesByID' => "
			SELECT c.title
			FROM userLikes AS ul
			LEFT JOIN categories AS c ON (c.id = ul.categoryID)
			WHERE userID = :id",
		
		'getUserDislikesByID' => "
			SELECT c.title
			FROM userDislikes AS ud
			LEFT JOIN categories AS c ON (c.id = ud.categoryID)
			WHERE userID = :id",
			
		'getRecentlyActivePeople' =>"
			SELECT DISTINCT f.contentID, f.contentTitle, f.contentVanityURL, f.userID, f.userFirstName, f.userLastName, f.userAvatar, f.userVanityURL, f.datetime, f.date, f.type
			FROM (SELECT p.id AS contentID, p.title AS contentTitle, p.vanityURL AS contentVanityURL, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, avatar AS userAvatar, u.vanityURL AS userVanityURL, p.datetime AS `datetime`, 'project' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, p.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(p.datetime,'%M %D, %Y at %l:%i %p'),
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
			LEFT JOIN users AS u ON (u.id = p.userID)
			UNION
			SELECT a.id AS contentID, a.title AS contentTitle, a.vanityURL AS contentVanityURL, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, avatar AS userAvatar, u.vanityURL AS userVanityURL, a.datetime AS `datetime`, 'article' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, a.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
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
			LEFT JOIN users AS u ON (u.id = a.userID)
			UNION
			SELECT u.id AS contentID, CONCAT_WS(' ',firstName,lastName) AS contentTitle, u.vanityURL AS contentVanityURL, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, avatar AS userAvatar, u.vanityURL AS userVanityURL, u.datetime AS `datetime`, 'person' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, u.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(u.datetime,'%M %D, %Y at %l:%i %p'),
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
			UNION
			SELECT c.id AS contentID, p.title AS contentTitle, p.vanityURL AS contentVanityURL, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, avatar AS userAvatar, u.vanityURL AS userVanityURL, c.datetime AS `datetime`, 'projectComment' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minute ago')
					)
				)
			) AS `date`
			FROM comments AS c
			INNER JOIN projectComments AS pc ON (c.id = pc.commentID)
			LEFT JOIN projects AS p ON (p.id = pc.projectID)
			LEFT JOIN users AS u ON (u.id = c.userID)
			UNION
			SELECT c.id AS contentID, a.title AS contentTitle, a.vanityURL AS contentVanityURL, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, avatar AS userAvatar, u.vanityURL AS userVanityURL, c.datetime AS `datetime`, 'articleComment' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minute ago')
					)
				)
			) AS `date`
			FROM comments AS c
			INNER JOIN articleComments AS ac ON (c.id = ac.commentID)
			LEFT JOIN articles AS a ON (a.id = ac.articleID)
			LEFT JOIN users AS u ON (u.id = c.userID)
			ORDER BY `datetime` DESC
			LIMIT 0,100) AS f
			GROUP BY f.userID
			ORDER BY f.datetime DESC
			LIMIT 0,16",
			
			
		/**
		* Project queries
		*/
		
		'getRecentProjects' => "
			SELECT id, title, description, vanityURL, `datetime`, 'project' AS `type`
			FROM projects
			ORDER BY `datetime` DESC
			LIMIT 0,5",
		
		'getProjectByVanityURL' => "
			SELECT id, userID, title, description, content, vanityURL, 'project' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(`datetime`,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minute ago')
					)
				)
			) AS `date`,
			IF(
				TIMESTAMPDIFF(MINUTE, completeDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, completeDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, completeDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(completeDate,'%M, %Y'),
						IF(
							TIMESTAMPDIFF(DAY, completeDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, completeDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, completeDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, completeDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, completeDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, completeDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, completeDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, completeDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, completeDate, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minute ago')
					)
				)
			) AS `completeDate`
			FROM projects
			WHERE vanityURL = :vanityURL
			",
			
			
		/**
		* Article queries
		*/
		
		'getRecentArticles' => "
			SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL
			FROM articles AS a
			LEFT JOIN users AS u ON (a.userID = u.id)
			LEFT JOIN privacyLevels AS p ON (a.privacyLevelID = p.id)
			WHERE p.title = 'Public'
			ORDER BY `datetime` DESC
			LIMIT 0,10",
		
		'getArticleByVanityURL' => "
			SELECT id, userID, title, description, content, vanityURL, 'article' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(`datetime`,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minute ago')
					)
				)
			) AS `date`
			FROM articles
			WHERE vanityURL = :vanityURL
			",
		
			
		/**
		* Event queries
		*/
		
		'getUpcomingEvents' => "
			SELECT id, userID, icsUID, title, description, vanityURL, location, `datetime`, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`
			FROM events
			WHERE TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) > -1
			ORDER BY startDateTime ASC
			LIMIT 0,3",
			
		'getUpcomingPublicEvents' => "
			SELECT e.id, userID, icsUID, e.title, description, vanityURL, location, `datetime`, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`
			FROM events AS e
			LEFT JOIN privacyLevels AS p ON (e.privacyLevelID = p.id)
			WHERE p.title = 'Public' AND TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) > -1
			ORDER BY startDateTime ASC
			LIMIT 0,10",
		
		'getEventByVanityURL' => "
			SELECT id, userID, icsUID, title, description, vanityURL, 'event' AS `type`, location, DATE_FORMAT(startDateTime,'%M %D, %Y at %l:%i %p') AS `date`, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`, IF(TIMESTAMPDIFF(YEAR,NOW(),startDateTime) > 0,DATE_FORMAT(startDateTime,'%Y'),NULL) AS `year`
			FROM events
			WHERE vanityURL = :vanityURL",
			
			
		/**
		* Job queries
		*/
			
		'getRecentJobs' => "
			SELECT j.id, userID, j.title, vanityURL, company, jt.title AS jobType, avatar, location, compensation, description, website
			FROM jobs AS j
			LEFT JOIN jobTypes AS jt ON (j.typeID = jt.id)
			ORDER BY `datetime` DESC
			LIMIT 0,3",
		
		'getJobByVanityURL' => "
			SELECT id, userID, title, description, vanityURL, 'job' AS `type`
			FROM jobs
			WHERE vanityURL = :vanityURL",
			
			
		/**
		* Comment count queries
		*/
		
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
			
			
		/**
		* Category queries
		*/
			
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
			
			
		/**
		* Team queries
		*/
			
		'getProjectTeam' => "
			SELECT u.id, firstName, lastName, avatar, vanityURL, title AS role
			FROM users AS u
			LEFT JOIN userProjects AS up ON (u.id = up.userID)
			WHERE up.projectID = :id
			ORDER BY lastName",
			
		'getArticleTeam' => "
			SELECT u.id, firstName, lastName, avatar, vanityURL, bio
			FROM users AS u
			LEFT JOIN userArticles AS ua ON (u.id = ua.userID)
			WHERE ua.articleID = :id
			ORDER BY lastName",
		
		'getCourseTeam' => "
			SELECT id, firstName, lastName, avatar, vanityURL, bio
			FROM users AS u
			LEFT JOIN teacherCourses AS tc ON (u.id = tc.userID)
			WHERE tc.courseID = :id",
			
			
		/**
		* Image queries
		*/
			
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
			ORDER BY i.id ASC",
			
		'getEventImages' => "
			SELECT id, title, description, `file`
			FROM images AS i
			LEFT JOIN eventImages AS ei ON(i.id = ei.imageID)
			WHERE ei.eventID = :id
			ORDER BY i.id ASC",
			
		'getJobImages' => "
			SELECT id, title, description, `file`
			FROM images AS i
			LEFT JOIN jobImages AS ji ON(i.id = ji.imageID)
			WHERE ji.jobID = :id
			ORDER BY i.id ASC",
			
			
		/**
		* Video queries
		*/
			
		'getProjectVideos' => "
			SELECT v.id, embedCode
			FROM videos AS v
			LEFT JOIN projectVideos AS pv ON(v.id = pv.videoID)
			WHERE pv.projectID = :id
			ORDER BY v.id ASC",
			
		'getArticleVideos' => "
			SELECT v.id, embedCode
			FROM videos AS v
			LEFT JOIN articleVideos AS av ON(v.id = av.videoID)
			WHERE av.articleID = :id
			ORDER BY v.id ASC",
			
		'getEventVideos' => "
			SELECT v.id, embedCode
			FROM videos AS v
			LEFT JOIN eventVideos AS ev ON(v.id = ev.videoID)
			WHERE ev.eventID = :id
			ORDER BY v.id ASC",
			
		'getJobVideos' => "
			SELECT v.id, embedCode
			FROM videos AS v
			LEFT JOIN jobVideos AS jv ON(v.id = jv.videoID)
			WHERE jv.jobID = :id
			ORDER BY v.id ASC",
			
			
		/**
		* Comment queries
		*/
			
		'getProjectComments' => "
			SELECT c.id, content, u.id AS userID, firstName, lastName, avatar, vanityURL,
			IF(
				TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minute ago')
					)
				)
			) AS `date`
			FROM comments AS c
			LEFT JOIN projectComments AS pc ON (c.id = pc.commentID)
			LEFT JOIN users AS u ON (u.id = c.userID)
			WHERE pc.projectID = :id
			ORDER BY `date` ASC",
			
		'getArticleComments' => "
			SELECT c.id, content, u.id AS userID, firstName, lastName, avatar, vanityURL,
			IF(
				TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minute ago')
					)
				)
			) AS `date`
			FROM comments AS c
			LEFT JOIN articleComments AS ac ON (c.id = ac.commentID)
			LEFT JOIN users AS u ON (u.id = c.userID)
			WHERE ac.articleID = :id
			ORDER BY `date` ASC",
		
		'getEventComments' => "
			SELECT c.id, content, u.id AS userID, firstName, lastName, avatar, vanityURL,
			IF(
				TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 23,
					IF(
						TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 14,
						DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, DATE_ADD(NOW(), INTERVAL 3 HOUR)), 'minute ago')
					)
				)
			) AS `date`
			FROM comments AS c
			LEFT JOIN eventComments AS ec ON (c.id = ec.commentID)
			LEFT JOIN users AS u ON (u.id = c.userID)
			WHERE ec.eventID = :id
			ORDER BY `date` ASC",
			
			
		/**
		* Link queries
		*/
			
		'getProjectLinks' => "
			SELECT l.id, title, link
			FROM links AS l
			LEFT JOIN projectLinks AS pl ON (l.id = pl.linkID)
			WHERE pl.projectID = :id
			ORDER BY title ASC",
			
		'getArticleLinks' => "
			SELECT l.id, title, link
			FROM links AS l
			LEFT JOIN articleLinks AS al ON (l.id = al.linkID)
			WHERE al.articleID = :id
			ORDER BY title ASC",
		
		'getCourseLinks' => "
			SELECT l.id, title, link
			FROM links AS l
			LEFT JOIN courseLinks AS cl ON (l.id = cl.linkID)
			WHERE cl.courseID = :id
			ORDER BY title ASC",
		
		'getEventLinks' => "
			SELECT l.id, title, link
			FROM links AS l
			LEFT JOIN eventLinks AS el ON (l.id = el.linkID)
			WHERE el.eventID = :id
			ORDER BY title ASC",
		
		'getJobLinks' => "
			SELECT l.id, title, link
			FROM links AS l
			LEFT JOIN jobLinks AS jl ON (l.id = jl.linkID)
			WHERE jl.jobID = :id
			ORDER BY title ASC"
			
	);
	
	public function __get($id){
		return $this->_info[$id];
	}
	
	public function __construct(){
		
	}
	
	public function __destruct(){
		
	}
}