<?php

namespace WDDSocial;

/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class SelectorSQL{
	
	# Switch NOW() to DATE_ADD(NOW(), INTERVAL 3 HOUR) when going live!!!
	
	private $_info = array(
		
		/**
		* Creates getDateDiff function
		*/
		
		'createDateTimeFunc' => '
			DELIMITER //

			CREATE FUNCTION getDateDiffEN(contentDate DATETIME)
				RETURNS VARCHAR(64)
				
				BEGIN
					IF TIMESTAMPDIFF(MINUTE, contentDate, NOW()) > 59
						THEN 
							IF TIMESTAMPDIFF(HOUR, contentDate, NOW()) > 23
								THEN
									IF TIMESTAMPDIFF(DAY, contentDate, NOW()) > 30
										THEN RETURN DATE_FORMAT(contentDate,"%M %D, %Y at %l:%i %p");
									ELSEIF TIMESTAMPDIFF(DAY, contentDate, NOW()) > 1
										THEN RETURN CONCAT_WS(" ", TIMESTAMPDIFF(DAY, contentDate, NOW()), "days ago");
									ELSE RETURN "Yesterday";
									END IF;
							ELSEIF TIMESTAMPDIFF(HOUR, contentDate, NOW()) > 1
								THEN RETURN CONCAT_WS(" ", TIMESTAMPDIFF(HOUR, contentDate, NOW()), "hours ago");
							ELSE RETURN CONCAT_WS(" ", TIMESTAMPDIFF(HOUR, contentDate, NOW()), "hour ago");
							END IF;
					ELSEIF TIMESTAMPDIFF(MINUTE, contentDate, NOW()) = 0
						THEN RETURN "Just now";
					ELSEIF TIMESTAMPDIFF(MINUTE, contentDate, NOW()) > 1
						THEN RETURN CONCAT_WS(" ", TIMESTAMPDIFF(MINUTE, contentDate, NOW()), "minutes ago");
					ELSE RETURN CONCAT_WS(" ", TIMESTAMPDIFF(MINUTE, contentDate, NOW()), "minute ago");
					END IF;
				END //
			
			DELIMITER ;',
		
		/**
		* Activity feed queries
		*/
			
		'getLatest' => "
			SELECT *
			FROM (SELECT p.id, title, description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT pf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 30,
							DATE_FORMAT(p.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, p.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM projects AS p
				LEFT JOIN users AS u ON (p.userID = u.id)
				LEFT JOIN projectFlags AS pf ON (p.id = pf.projectID)
				GROUP BY p.id
				UNION
				SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT af.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 30,
							DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, a.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM articles AS a
				LEFT JOIN users AS u ON (a.userID = u.id)
				LEFT JOIN articleFlags AS af ON (a.id = af.articleID)
				GROUP BY a.id
				UNION
				SELECT id, CONCAT_WS(' ', firstName, lastName) AS title, bio AS description, vanityURL, `DATETIME`, 'person' AS `TYPE`, id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, vanityURL AS userURL, 0 AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, u.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, u.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, u.datetime, NOW()) > 30,
							DATE_FORMAT(u.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, u.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, u.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, u.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, u.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, u.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, u.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, u.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, u.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, u.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM users AS u
				ORDER BY DATETIME DESC) AS latest
			WHERE latest.flagCount < 3",
		
		'getUserLatestWithComments' => "
			SELECT *
			FROM (SELECT p.id, p.title, description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT pf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 30,
							DATE_FORMAT(p.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, p.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM projects AS p
				LEFT JOIN users AS u ON (p.userID = u.id)
				LEFT JOIN userProjects AS up ON (p.id = up.projectID)
				LEFT JOIN projectFlags AS pf ON (p.id = pf.projectID)
				WHERE u.id = :id OR up.userID = :id
				GROUP BY p.id
				UNION
				SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT af.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 30,
							DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, a.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM articles AS a
				LEFT JOIN users AS u ON (a.userID = u.id)
				LEFT JOIN userArticles AS ua ON (a.id = ua.articleID)
				LEFT JOIN articleFlags AS af ON (a.id = af.articleID)
				WHERE u.id = :id OR ua.userID = :id
				GROUP BY a.id
				UNION
				SELECT p.id, p.title, c.content, p.vanityURL, c.datetime, 'projectComment' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT cf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 30,
							DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM comments AS c
				INNER JOIN projectComments AS pc ON (c.id = pc.commentID)
				LEFT JOIN projects AS p ON (p.id = pc.projectID)
				LEFT JOIN users AS u ON (u.id = c.userID)
				LEFT JOIN commentFlags AS cf ON (c.id = cf.commentID)
				WHERE u.id = :id
				GROUP BY c.id
				UNION
				SELECT a.id, a.title, c.content, a.vanityURL, c.datetime, 'articleComment' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT cf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 30,
							DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM comments AS c
				INNER JOIN articleComments AS ac ON (c.id = ac.commentID)
				LEFT JOIN articles AS a ON (a.id = ac.articleID)
				LEFT JOIN users AS u ON (u.id = c.userID)
				LEFT JOIN commentFlags AS cf ON (c.id = cf.commentID)
				WHERE u.id = :id
				GROUP BY c.id
				UNION
				SELECT e.id, e.title, c.content, e.vanityURL, c.datetime, 'eventComment' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT cf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 30,
							DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM comments AS c
				INNER JOIN eventComments AS ec ON (c.id = ec.commentID)
				LEFT JOIN events AS e ON (e.id = ec.eventID)
				LEFT JOIN users AS u ON (u.id = c.userID)
				LEFT JOIN commentFlags AS cf ON (c.id = cf.commentID)
				WHERE u.id = :id
				GROUP BY c.id
				ORDER BY `datetime` DESC) AS userLatest
			WHERE userLatest.flagCount < 3",
		
		'getUserLatest' => "
			SELECT *
			FROM (SELECT p.id, p.title, description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT pf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 30,
							DATE_FORMAT(p.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, p.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM projects AS p
				LEFT JOIN users AS u ON (p.userID = u.id)
				LEFT JOIN userProjects AS up ON (p.id = up.projectID)
				LEFT JOIN projectFlags AS pf ON (p.id = pf.projectID)
				WHERE u.id = :id OR up.userID = :id
				GROUP BY p.id
				UNION
				SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT af.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 30,
							DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, a.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM articles AS a
				LEFT JOIN users AS u ON (a.userID = u.id)
				LEFT JOIN userArticles AS ua ON (a.id = ua.articleID)
				LEFT JOIN articleFlags AS af ON (a.id = af.articleID)
				WHERE u.id = :id OR ua.userID = :id
				GROUP BY a.id
				ORDER BY `datetime` DESC) AS userLatest
			WHERE userLatest.flagCount < 3",
		
		'getUserPublicLatest' => "
			SELECT *
			FROM (SELECT p.id, p.title, description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT pf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 30,
							DATE_FORMAT(p.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, p.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM projects AS p
				LEFT JOIN users AS u ON (p.userID = u.id)
				LEFT JOIN userProjects AS up ON (p.id = up.projectID)
				LEFT JOIN projectFlags AS pf ON (p.id = pf.projectID)
				WHERE u.id = :id OR up.userID = :id
				GROUP BY p.id
				UNION
				SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT af.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 30,
							DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, a.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM articles AS a
				LEFT JOIN users AS u ON (a.userID = u.id)
				LEFT JOIN userArticles AS ua ON (a.id = ua.articleID)
				LEFT JOIN articleFlags AS af ON (a.id = af.articleID)
				LEFT JOIN privacyLevels AS pl ON (a.privacyLevelID = pl.id)
				WHERE (u.id = :id OR ua.userID = :id) AND pl.title = 'public'
				GROUP BY a.id
				ORDER BY `datetime` DESC) AS userLatest
			WHERE userLatest.flagCount < 3",
			
			
		/**
		* People queries
		*/
		
		'getUserByID' => "
			SELECT u.id, firstName, lastName, email, fullsailEmail, avatar, vanityURL, bio, hometown, DATE_FORMAT(birthday,'%M %e, %Y') AS birthday, TIMESTAMPDIFF(YEAR, birthday, NOW()) AS age, ut.title AS `type`, ut.id as typeID, website, twitter, facebook, github, dribbble, forrst
			FROM users AS u
			LEFT JOIN userTypes AS ut ON (u.typeID = ut.id)
			WHERE u.id = :id
			LIMIT 1",
		
		'getUserAvatarByID' => "
			SELECT avatar
			FROM users
			WHERE id = :id
			LIMIT 1",
		
		'getUserEmailByID' => "
			SELECT email
			FROM users
			WHERE id = :id
			LIMIT 1",
		
		'getUserFullSailEmailByID' => "
			SELECT fullsailEmail AS email
			FROM users
			WHERE id = :id
			LIMIT 1",
		
		'getUserByLogin' => "
			SELECT u.id, firstName, lastName, avatar, vanityURL, ut.title AS `type`, languageID as `lang`
			FROM users AS u
			LEFT JOIN userTypes AS ut ON (u.typeID = ut.id)
			WHERE (email = :email OR fullsailEmail = :email) AND `password` = MD5(:password)
			LIMIT 1",
		
		'changeFullsailEmailInfo' => "
			SELECT id, firstName, lastName, verified, verificationCode
			FROM users
			WHERE (email = :email) AND `password` = MD5(:password)
			LIMIT 1
		",
		
		'getUserSessionDataByID' => "
			SELECT u.id, firstName, lastName, avatar, vanityURL, ut.title AS `type`
			FROM users AS u
			LEFT JOIN userTypes AS ut ON (u.typeID = ut.id)
			WHERE u.id = :id
			LIMIT 1",
		
		'getUserIDByVanityURL' => "
			SELECT id
			FROM users
			WHERE vanityURL = :vanityURL
			LIMIT 1",
		
		'getUserByVanityURL' => "
			SELECT u.id, firstName, lastName, avatar, vanityURL, bio, hometown, TIMESTAMPDIFF(YEAR, birthday, NOW()) AS age, ut.title AS `type`, ut.id as typeID, website, twitter, facebook, github, dribbble, forrst
			FROM users AS u
			LEFT JOIN userTypes AS ut ON (u.typeID = ut.id)
			WHERE vanityURL = :vanityURL
			LIMIT 1",
		
		'getUserByName' => "
			SELECT id
			FROM users
			WHERE CONCAT_WS(' ',firstName, lastName) = :name
			LIMIT 1",
		
		'getUserByVerificationCode' => "
			SELECT id
			FROM users
			WHERE verificationCode = :verificationCode
			LIMIT 1",
		
		'getUserByFullsailEmail' => "
			SELECT id, firstName
			FROM users
			WHERE fullsailEmail = :email
			LIMIT 1",
		
		'getUserVerificationCode' => "
			SELECT verificationCode
			FROM users
			WHERE id = :id
			LIMIT 1",
		
		'getUserDetailByID' => "
			SELECT DATE_FORMAT(startDate,'%M, %Y') AS startDate, DATE_FORMAT(startDate,'%Y-%m-%d') as startDateInput, DATE_FORMAT(graduationDate,'%M, %Y') AS graduationDate, DATE_FORMAT(graduationDate,'%Y-%m-%d') as graduationDateInput, location, employerTitle, employerLink
			FROM userDetail
			WHERE userID = :id
			LIMIT 1",
		
		'getTeacherCoursesByID' => "
			SELECT id, title, `month`
			FROM teacherCourses AS tc
			LEFT JOIN courses AS c ON (c.id = tc.courseID)
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
				TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 23,
					IF(
						TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 30,
						DATE_FORMAT(p.datetime,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, p.datetime, NOW()), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minute ago')
					)
				)
			) AS `date`
			FROM projects AS p
			LEFT JOIN users AS u ON (u.id = p.userID)
			UNION
			SELECT a.id AS contentID, a.title AS contentTitle, a.vanityURL AS contentVanityURL, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, avatar AS userAvatar, u.vanityURL AS userVanityURL, a.datetime AS `datetime`, 'article' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 23,
					IF(
						TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 30,
						DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, a.datetime, NOW()), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minute ago')
					)
				)
			) AS `date`
			FROM articles AS a
			LEFT JOIN users AS u ON (u.id = a.userID)
			UNION
			SELECT u.id AS contentID, CONCAT_WS(' ',firstName,lastName) AS contentTitle, u.vanityURL AS contentVanityURL, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, avatar AS userAvatar, u.vanityURL AS userVanityURL, u.datetime AS `datetime`, 'person' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, u.datetime, NOW()) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, u.datetime, NOW()) > 23,
					IF(
						TIMESTAMPDIFF(DAY, u.datetime, NOW()) > 30,
						DATE_FORMAT(u.datetime,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, u.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, u.datetime, NOW()), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, u.datetime, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, u.datetime, NOW()), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, u.datetime, NOW()), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, u.datetime, NOW()) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, u.datetime, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, u.datetime, NOW()), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, u.datetime, NOW()), 'minute ago')
					)
				)
			) AS `date`
			FROM users AS u
			UNION
			SELECT c.id AS contentID, p.title AS contentTitle, p.vanityURL AS contentVanityURL, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, avatar AS userAvatar, u.vanityURL AS userVanityURL, c.datetime AS `datetime`, 'projectComment' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 23,
					IF(
						TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 30,
						DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, NOW()), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minute ago')
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
				TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 23,
					IF(
						TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 30,
						DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, NOW()), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minute ago')
					)
				)
			) AS `date`
			FROM comments AS c
			INNER JOIN articleComments AS ac ON (c.id = ac.commentID)
			LEFT JOIN articles AS a ON (a.id = ac.articleID)
			LEFT JOIN users AS u ON (u.id = c.userID)
			UNION
			SELECT c.id AS contentID, e.title AS contentTitle, e.vanityURL AS contentVanityURL, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, avatar AS userAvatar, u.vanityURL AS userVanityURL, c.datetime AS `datetime`, 'eventComment' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 23,
					IF(
						TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 30,
						DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, NOW()), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minute ago')
					)
				)
			) AS `date`
			FROM comments AS c
			INNER JOIN eventComments AS ec ON (c.id = ec.commentID)
			LEFT JOIN events AS e ON (e.id = ec.eventID)
			LEFT JOIN users AS u ON (u.id = c.userID)
			ORDER BY `datetime` DESC
			LIMIT 0,100) AS f
			GROUP BY f.userID
			ORDER BY f.datetime DESC
			LIMIT 0,16",
		
		'getPeople' => "
			SELECT u.id, firstName, lastName, avatar, vanityURL, bio, hometown, TIMESTAMPDIFF(YEAR, birthday, NOW()) AS age, ut.title AS `type`, ut.id as typeID,
			IF(
				TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, `datetime`, NOW()) > 23,
					IF(
						TIMESTAMPDIFF(DAY, `datetime`, NOW()) > 30,
						DATE_FORMAT(`datetime`,'%M %D, %Y'),
						IF(
							TIMESTAMPDIFF(DAY, `datetime`, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, `datetime`, NOW()), 'days ago'),
							'yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, `datetime`, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, NOW()), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, NOW()), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) = 0,
					'just now',
					IF(
						TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, NOW()), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, NOW()), 'minute ago')
					)
				)
			) AS `date`
			FROM users as u
			LEFT JOIN userTypes AS ut ON (u.typeID = ut.id)",
			
			
		/**
		* Project queries
		*/
		
		'getRecentProjects' => "
			SELECT *
			FROM (SELECT p.id, title, description, vanityURL, p.datetime, 'project' AS `type`, COUNT(DISTINCT pf.userID) AS flagCount
				FROM projects AS p
				LEFT JOIN projectFlags AS pf ON (p.id = pf.projectID)
				GROUP BY p.id
				ORDER BY `datetime` DESC) AS projects
			WHERE projects.flagCount < 3
			ORDER BY RAND()
			LIMIT 0, 10",
		
		'getProjectByVanityURL' => "
			SELECT id, userID, title, description, content, vanityURL, 'project' AS `type`, DATE_FORMAT(completeDate,'%M, %Y') AS `completeDate`, DATE_FORMAT(completeDate, '%Y-%m-%d') AS `completeDateInput`,
			IF(
				TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, `datetime`, NOW()) > 23,
					IF(
						TIMESTAMPDIFF(DAY, `datetime`, NOW()) > 30,
						DATE_FORMAT(`datetime`,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, `datetime`, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, `datetime`, NOW()), 'days ago'),
							'yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, `datetime`, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, NOW()), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, NOW()), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) = 0,
					'less than a minute ago',
					IF(
						TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, NOW()), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, NOW()), 'minute ago')
					)
				)
			) AS `date`
			FROM projects
			WHERE vanityURL = :vanityURL
			LIMIT 1",
		
		'getProjectByID' => "
			SELECT id, userID, title, description, content, vanityURL, 'project' AS `type`, DATE_FORMAT(completeDate,'%M, %Y') AS `completeDate`, DATE_FORMAT(completeDate, '%Y-%m-%d') AS `completeDateInput`,
			IF(
				TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, `datetime`, NOW()) > 23,
					IF(
						TIMESTAMPDIFF(DAY, `datetime`, NOW()) > 30,
						DATE_FORMAT(`datetime`,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, `datetime`, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, `datetime`, NOW()), 'days ago'),
							'yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, `datetime`, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, NOW()), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, NOW()), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) = 0,
					'less than a minute ago',
					IF(
						TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, NOW()), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, NOW()), 'minute ago')
					)
				)
			) AS `date`
			FROM projects
			WHERE id = :id
			LIMIT 1",
		
		'getProjectVanityURL' => "
			SELECT vanityURL
			FROM projects
			WHERE id = :id
			LIMIT 1",
		
		'getProjectFlagCount' => "
			SELECT COUNT(DISTINCT userID) AS flagCount
			FROM projectFlags
			WHERE projectID = :id",
		
		'getProjects' => "
			SELECT *
			FROM (SELECT p.id, title, description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT pf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 30,
							DATE_FORMAT(p.datetime,'%M %D, %Y'),
							IF(
								TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, p.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM projects AS p
				LEFT JOIN users AS u ON (p.userID = u.id)
				LEFT JOIN projectFlags AS pf ON (p.id = pf.projectID)
				GROUP BY p.id) AS projects
			WHERE projects.flagCount < 3",
		
		'getProjectsOwnedByUser' => "
			SELECT id, COUNT(DISTINCT up.userID) AS memberCount
			FROM projects AS p
			LEFT JOIN userProjects AS up ON (p.id = up.projectID)
			WHERE p.userID = :userID
			GROUP BY p.id",
			
			
		/**
		* Article queries
		*/
		
		'getRecentArticles' => "
			SELECT *
			FROM (SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT af.userID) AS flagCount
				FROM articles AS a
				LEFT JOIN users AS u ON (a.userID = u.id)
				LEFT JOIN privacyLevels AS p ON (a.privacyLevelID = p.id)
				LEFT JOIN articleFlags AS af ON (a.id = af.articleID)
				WHERE p.title = 'Public'
				GROUP BY a.id
				ORDER BY a.datetime DESC) AS articles
			WHERE articles.flagCount < 3",
		
		'getArticleByVanityURL' => "
			SELECT id, userID, title, description, content, vanityURL, 'article' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, `datetime`, NOW()) > 23,
					IF(
						TIMESTAMPDIFF(DAY, `datetime`, NOW()) > 30,
						DATE_FORMAT(`datetime`,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, `datetime`, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, `datetime`, NOW()), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, `datetime`, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, NOW()), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, NOW()), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, NOW()), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, NOW()), 'minute ago')
					)
				)
			) AS `date`
			FROM articles
			WHERE vanityURL = :vanityURL
			LIMIT 1",
		
		'getArticleByID' => "
			SELECT id, userID, title, description, content, vanityURL, 'article' AS `type`,
			IF(
				TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, `datetime`, NOW()) > 23,
					IF(
						TIMESTAMPDIFF(DAY, `datetime`, NOW()) > 30,
						DATE_FORMAT(`datetime`,'%M %D, %Y at %l:%i %p'),
						IF(
							TIMESTAMPDIFF(DAY, `datetime`, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, `datetime`, NOW()), 'days ago'),
							'Yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, `datetime`, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, NOW()), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, NOW()), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) = 0,
					'Just now',
					IF(
						TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, NOW()), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, NOW()), 'minute ago')
					)
				)
			) AS `date`
			FROM articles
			WHERE id = :id
			LIMIT 1",
		
		'getArticleVanityURL' => "
			SELECT vanityURL
			FROM articles
			WHERE id = :id
			LIMIT 1",
		
		'getArticleFlagCount' => "
			SELECT COUNT(DISTINCT userID) AS flagCount
			FROM articleFlags
			WHERE articleID = :id",
		
		'getArticles' => "
			SELECT *
			FROM (SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT af.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 30,
							DATE_FORMAT(a.datetime,'%M %D, %Y'),
							IF(
								TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, a.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM articles AS a
				LEFT JOIN users AS u ON (a.userID = u.id)
				LEFT JOIN articleFlags AS af ON (a.id = af.articleID)
				GROUP BY a.id) AS articles
			WHERE articles.flagCount < 3",
		
		'getPublicArticles' => "
			SELECT *
			FROM (SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT af.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 30,
							DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, a.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM articles AS a
				LEFT JOIN users AS u ON (a.userID = u.id)
				LEFT JOIN privacyLevels AS pl ON (a.privacyLevelID = pl.id)
				LEFT JOIN articleFlags AS af ON (a.id = af.articleID)
				WHERE pl.title = 'public'
				GROUP BY a.id) AS articles
			WHERE articles.flagCount < 3",
		
		'getArticlesOwnedByUser' => "
			SELECT id, COUNT(DISTINCT ua.userID) AS authorCount
			FROM articles AS a
			LEFT JOIN userArticles AS ua ON (a.id = ua.articleID)
			WHERE a.userID = :userID
			GROUP BY a.id",
		
			
		/**
		* Event queries
		*/
		
		'getUpcomingEvents' => "
			SELECT *
			FROM (SELECT id, e.userID, icsUID, title, description, vanityURL, location, e.datetime, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`, COUNT(DISTINCT ef.userID) AS flagCount
				FROM events AS e
				LEFT JOIN eventFlags AS ef ON (e.id = ef.eventID)
				WHERE TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) > -1
				GROUP BY e.id
				ORDER BY startDateTime ASC) AS events
			WHERE events.flagCount < 3
			LIMIT 0,3",
			
		'getUpcomingPublicEvents' => "
			SELECT *
			FROM (SELECT e.id, e.userID, icsUID, e.title, description, vanityURL, location, e.datetime, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`, COUNT(DISTINCT ef.userID) AS flagCount
				FROM events AS e
				LEFT JOIN privacyLevels AS p ON (e.privacyLevelID = p.id)
				LEFT JOIN eventFlags AS ef ON (e.id = ef.eventID)
				WHERE p.title = 'Public' AND TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) > -1
				GROUP BY e.id
				ORDER BY startDateTime ASC) AS events
			WHERE events.flagCount < 3
			LIMIT 0,3",
		
		'getExpiredEvents' => "
			SELECT icsUID
			FROM events AS e
			WHERE TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) < 0",
		
		'getEventByVanityURL' => "
			SELECT id, userID, icsUID, title, description, content, vanityURL, 'event' AS `type`, location, DATE_FORMAT(startDateTime,'%M %D, %Y at %l:%i %p') AS `date`, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%M %e, %Y') AS `startDate`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`, IF(TIMESTAMPDIFF(YEAR,NOW(),startDateTime) > 0,DATE_FORMAT(startDateTime,'%Y'),NULL) AS `year`, DATE_FORMAT(startDateTime, '%Y-%m-%d') AS `startDateInput`, DATE_FORMAT(startDateTime,'%k:%i:%s') AS `startTimeInput`, TIMESTAMPDIFF(HOUR,startDateTime,endDateTime) AS duration
			FROM events
			WHERE vanityURL = :vanityURL
			LIMIT 1",
		
		'getEventByID' => "
			SELECT id, userID, icsUID, title, description, content, vanityURL, 'event' AS `type`, location, DATE_FORMAT(startDateTime,'%M %D, %Y at %l:%i %p') AS `date`, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%M %e, %Y') AS `startDate`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`, IF(TIMESTAMPDIFF(YEAR,NOW(),startDateTime) > 0,DATE_FORMAT(startDateTime,'%Y'),NULL) AS `year`, DATE_FORMAT(startDateTime, '%Y-%m-%d') AS `startDateInput`, DATE_FORMAT(startDateTime,'%k:%i:%s') AS `startTimeInput`, TIMESTAMPDIFF(HOUR,startDateTime,endDateTime) AS duration
			FROM events
			WHERE id = :id
			LIMIT 1",
		
		'getEventCommentData' => "
			SELECT location, DATE_FORMAT(startDateTime,'%M %D, %Y at %l:%i %p') AS `date`, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`
			FROM events
			WHERE id = :id
			LIMIT 1",
		
		'getEventICSValues' => "
			SELECT id, icsUID AS uid, title, description, location, vanityURL, DATE_FORMAT(startDatetime, '%Y%m%dT%H%i%S') AS start, DATE_FORMAT(endDatetime, '%Y%m%dT%H%i%S') AS end, DATE_FORMAT(`datetime`, '%Y%m%dT%H%i%SZ') AS created
			FROM events
			WHERE id = :id",
		
		'getEventVanityURL' => "
			SELECT vanityURL
			FROM events
			WHERE id = :id
			LIMIT 1",
		
		'getEventFlagCount' => "
			SELECT COUNT(DISTINCT userID) AS flagCount
			FROM eventFlags
			WHERE eventID = :id",
		
		'hasEventExpired' => "
			SELECT id
			FROM events
			WHERE id = :id AND TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) > -1",
		
		'getEvents' => "
			SELECT *
			FROM(SELECT e.id, e.userID, icsUID, e.title, description, vanityURL, 'event' AS `type`, location, e.datetime, startDateTime, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`, COUNT(DISTINCT ef.userID) AS flagCount
				FROM events AS e
				LEFT JOIN eventFlags AS ef ON (e.id = ef.eventID)
				WHERE TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) > -1
				GROUP BY e.id) AS events
			WHERE events.flagCount < 3",
		
		'getPublicEvents' => "
			SELECT *
			FROM(SELECT e.id, e.userID, icsUID, e.title, description, vanityURL, 'event' AS `type`, location, e.datetime, startDateTime, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`, COUNT(DISTINCT ef.userID) AS flagCount
				FROM events AS e
				LEFT JOIN privacyLevels AS p ON (e.privacyLevelID = p.id)
				LEFT JOIN eventFlags AS ef ON (e.id = ef.eventID)
				WHERE p.title = 'Public' AND TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) > -1
				GROUP BY e.id) AS events
			WHERE events.flagCount < 3",
			
			
			
		/**
		* Job queries
		*/
			
		'getRecentJobs' => "
			SELECT *
			FROM (SELECT j.id, j.userID, j.title, vanityURL, company, jt.title AS jobType, avatar, location, compensation, description, website, COUNT(DISTINCT jf.userID) AS flagCount
				FROM jobs AS j
				LEFT JOIN jobTypes AS jt ON (j.typeID = jt.id)
				LEFT JOIN jobFlags AS jf ON (j.id = jf.jobID)
				GROUP BY j.id
				ORDER BY j.datetime DESC) AS jobs
			WHERE jobs.flagCount < 3
			LIMIT 0,3",
		
		'getJobByVanityURL' => "
			SELECT j.id, userID, j.title, description, content, vanityURL, 'job' AS `type`, company, jt.id AS jobTypeID, jt.title AS jobType, avatar, location, compensation, website, email
			FROM jobs AS j
			LEFT JOIN jobTypes AS jt ON (j.typeID = jt.id)
			WHERE vanityURL = :vanityURL
			LIMIT 1",
		
		'getJobByID' => "
			SELECT j.id, userID, j.title, description, content, vanityURL, 'job' AS `type`, company, jt.id AS jobTypeID, jt.title AS jobType, avatar, location, compensation, website, email
			FROM jobs AS j
			LEFT JOIN jobTypes AS jt ON (j.typeID = jt.id)
			WHERE j.id = :id
			LIMIT 1",
		
		'getJobVanityURL' => "
			SELECT vanityURL
			FROM jobs
			WHERE id = :id
			LIMIT 1",
		
		'getJobFlagCount' => "
			SELECT COUNT(DISTINCT userID) AS flagCount
			FROM jobFlags
			WHERE jobID = :id",
		
		'getJobAvatar' => "
			SELECT avatar
			FROM jobs
			WHERE id = :id",
		
		'getJobs' => "
			SELECT *
			FROM (SELECT j.id, j.userID, j.title, vanityURL, company, j.datetime, jt.title AS jobType, avatar, location, compensation, description, website, COUNT(DISTINCT jf.userID) AS flagCount
				FROM jobs AS j
				LEFT JOIN jobTypes AS jt ON (j.typeID = jt.id)
				LEFT JOIN jobFlags AS jf ON (j.id = jf.jobID)
				GROUP BY j.id
				ORDER BY j.datetime DESC) AS jobs
			WHERE jobs.flagCount < 3",
			
			
		/**
		* Category queries
		*/
		
		'getCategoryIDByTitle' => "
			SELECT id
			FROM categories
			WHERE title = :title
			LIMIT 1",
		
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
			SELECT id, title, `file`
			FROM images AS i
			LEFT JOIN projectImages AS pi ON(i.id = pi.imageID)
			WHERE pi.projectID = :id
			ORDER BY i.id ASC",
			
		'getArticleImages' => "
			SELECT id, title, `file`
			FROM images AS i
			LEFT JOIN articleImages AS ai ON(i.id = ai.imageID)
			WHERE ai.articleID = :id
			ORDER BY i.id ASC",
			
		'getEventImages' => "
			SELECT id, title, `file`
			FROM images AS i
			LEFT JOIN eventImages AS ei ON(i.id = ei.imageID)
			WHERE ei.eventID = :id
			ORDER BY i.id ASC",
			
		'getJobImages' => "
			SELECT id, title, `file`
			FROM images AS i
			LEFT JOIN jobImages AS ji ON(i.id = ji.imageID)
			WHERE ji.jobID = :id
			ORDER BY i.id ASC",
		
		'getImageFilename' => "
			SELECT `file`
			FROM images
			WHERE id = :id",
			
			
		/**
		* Video queries
		*/
			
		'getVideoID' => "
			SELECT id
			FROM videos
			WHERE embedCode = :embedCode",
		
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
		* Course queries
		*/
		
		'getCourses' => "
			SELECT id, title, description, `month`
			FROM courses",
		
		'getCourseByID' => "
			SELECT id, title, description, `month`
			FROM courses
			WHERE id = :id",
		
		'getCourseCategories' => "
			SELECT c.id, c.title
			FROM categories AS c
			LEFT JOIN courseCategories AS cc ON (c.id = cc.categoryID)
			WHERE cc.courseID = :id",
		
		'getCourseTeachers' => "
			SELECT id, firstName, lastName, avatar, bio, vanityURL
			FROM users AS u
			LEFT JOIN teacherCourses AS tc ON (u.id = tc.userID)
			WHERE tc.courseID = :id
			ORDER BY u.lastName",
			
		'getProjectCourses' => "
			SELECT id, title
			FROM courses AS c
			LEFT JOIN projectCourses AS pc ON (c.id = pc.courseID)
			WHERE pc.projectID = :id",
		
		'getArticleCourses' => "
			SELECT id, title
			FROM courses AS c
			LEFT JOIN articleCourses AS ac ON (c.id = ac.courseID)
			WHERE ac.articleID = :id",
		
		'getEventCourses' => "
			SELECT id, title
			FROM courses AS c
			LEFT JOIN eventCourses AS ec ON (c.id = ec.courseID)
			WHERE ec.eventID = :id",
		
		'getCourseLatest' => "
			SELECT *
			FROM (SELECT p.id, p.title, description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT pf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 30,
							DATE_FORMAT(p.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, p.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM projects AS p
				LEFT JOIN users AS u ON (p.userID = u.id)
				LEFT JOIN userProjects AS up ON (p.id = up.projectID)
				LEFT JOIN projectFlags AS pf ON (p.id = pf.projectID)
				LEFT JOIN projectCourses AS pc ON (p.id = pc.projectID)
				WHERE pc.courseID = :id
				GROUP BY p.id
				UNION
				SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT af.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 30,
							DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, a.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM articles AS a
				LEFT JOIN users AS u ON (a.userID = u.id)
				LEFT JOIN userArticles AS ua ON (a.id = ua.articleID)
				LEFT JOIN articleFlags AS af ON (a.id = af.articleID)
				LEFT JOIN articleCourses AS ac ON (a.id = ac.articleID)
				WHERE ac.courseID = :id
				GROUP BY a.id
				ORDER BY `datetime` DESC) AS userLatest
			WHERE userLatest.flagCount < 3",
		
		'getCoursePublicLatest' => "
			SELECT *
			FROM (SELECT p.id, p.title, description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT pf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 30,
							DATE_FORMAT(p.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, p.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM projects AS p
				LEFT JOIN users AS u ON (p.userID = u.id)
				LEFT JOIN userProjects AS up ON (p.id = up.projectID)
				LEFT JOIN projectFlags AS pf ON (p.id = pf.projectID)
				LEFT JOIN projectCourses AS pc ON (p.id = pc.projectID)
				WHERE pc.courseID = :id
				GROUP BY p.id
				UNION
				SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT af.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 30,
							DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, a.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM articles AS a
				LEFT JOIN users AS u ON (a.userID = u.id)
				LEFT JOIN userArticles AS ua ON (a.id = ua.articleID)
				LEFT JOIN articleFlags AS af ON (a.id = af.articleID)
				LEFT JOIN privacyLevels AS pl ON (a.privacyLevelID = pl.id)
				LEFT JOIN articleCourses AS ac ON (a.id = ac.articleID)
				WHERE ac.courseID = :id AND pl.title = 'public'
				GROUP BY a.id
				ORDER BY `datetime` DESC) AS userLatest
			WHERE userLatest.flagCount < 3",
			
			
			
		/**
		* Comment queries
		*/
			
		'getCommentByID' => "
			SELECT id, content, userID
			FROM comments
			WHERE id = :id",
		
		'getProjectComments' => "
			SELECT *
			FROM (SELECT c.id, content, u.id AS userID, firstName, lastName, avatar, vanityURL, COUNT(DISTINCT cf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 30,
							DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM comments AS c
				LEFT JOIN projectComments AS pc ON (c.id = pc.commentID)
				LEFT JOIN users AS u ON (u.id = c.userID)
				LEFT JOIN commentFlags AS cf ON (c.id = cf.commentID)
				WHERE pc.projectID = :id
				GROUP BY c.id
				ORDER BY c.datetime ASC) AS comments
			WHERE comments.flagCount < 3",
			
		'getArticleComments' => "
			SELECT *
			FROM (SELECT c.id, content, u.id AS userID, firstName, lastName, avatar, vanityURL, COUNT(DISTINCT cf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 30,
							DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM comments AS c
				LEFT JOIN articleComments AS ac ON (c.id = ac.commentID)
				LEFT JOIN users AS u ON (u.id = c.userID)
				LEFT JOIN commentFlags AS cf ON (c.id = cf.commentID)
				WHERE ac.articleID = :id
				GROUP BY c.id
				ORDER BY c.datetime ASC) AS comments
			WHERE comments.flagCount < 3",
		
		'getEventComments' => "
			SELECT *
			FROM (SELECT c.id, content, u.id AS userID, firstName, lastName, avatar, vanityURL, COUNT(DISTINCT cf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 30,
							DATE_FORMAT(c.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, c.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, c.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, c.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, c.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, c.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM comments AS c
				LEFT JOIN eventComments AS ec ON (c.id = ec.commentID)
				LEFT JOIN users AS u ON (u.id = c.userID)
				LEFT JOIN commentFlags AS cf ON (c.id = cf.commentID)
				WHERE ec.eventID = :id
				GROUP BY c.id
				ORDER BY c.datetime ASC) AS comments
			WHERE comments.flagCount < 3",
		
		'getCommentProject' => "
			SELECT c.id, c.userID, c.content, p.id AS contentID, p.vanityURL, 'project' AS `type`
			FROM comments AS c
			LEFT JOIN projectComments AS pc ON (c.id = pc.commentID)
			LEFT JOIN projects AS p ON (p.id = pc.projectID)
			WHERE c.id = :id",
		
		'getCommentArticle' => "
			SELECT c.id, c.userID, c.content, a.id AS contentID, a.vanityURL, 'article' AS `type`
			FROM comments AS c
			LEFT JOIN articleComments AS ac ON (c.id = ac.commentID)
			LEFT JOIN articles AS a ON (a.id = ac.articleID)
			WHERE c.id = :id",
		
		'getCommentEvent' => "
			SELECT c.id, c.userID, c.content, e.id AS contentID, e.vanityURL, 'event' AS `type`
			FROM comments AS c
			LEFT JOIN eventComments AS ec ON (c.id = ec.commentID)
			LEFT JOIN events AS e ON (e.id = ec.eventID)
			WHERE c.id = :id",
			
			
		/**
		* Comment count queries
		*/
		
		'getProjectCommentsCount' => "
			SELECT COUNT(*) as comments
			FROM (SELECT pc.projectID, COUNT(DISTINCT cf.userID) AS flagCount
				FROM projectComments AS pc
				LEFT JOIN commentFlags AS cf ON (pc.commentID = cf.commentID)
				WHERE pc.projectID = :id
				GROUP BY pc.commentID) AS c
			WHERE c.flagCount < 3",
		
		'getArticleCommentsCount' => "
			SELECT COUNT(*) as comments
			FROM (SELECT ac.articleID, COUNT(DISTINCT cf.userID) AS flagCount
				FROM articleComments AS ac
				LEFT JOIN commentFlags AS cf ON (ac.commentID = cf.commentID)
				WHERE ac.articleID = :id
				GROUP BY ac.commentID) AS c
			WHERE c.flagCount < 3",
		
		'getEventCommentsCount' => "
			SELECT COUNT(*) as comments
			FROM (SELECT ec.eventID, COUNT(DISTINCT cf.userID) AS flagCount
				FROM eventComments AS ec
				LEFT JOIN commentFlags AS cf ON (ec.commentID = cf.commentID)
				WHERE ec.eventID = :id
				GROUP BY ec.commentID) AS c
			WHERE c.flagCount < 3",
			
			
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
			ORDER BY title ASC",
			
			
		/**
		* Category queries
		*/
			
		'getCategoryByTitle' => "
			SELECT id
			FROM categories
			WHERE title = :title
			LIMIT 1",
			
			
		/**
		* Course queries
		*/
			
		'getCourse' => "
			SELECT id
			FROM courses
			WHERE id = :id OR title = :title
			LIMIT 1",
			
			
		/**
		* Form queries
		*/
			
		'getUserTypeIDByTitle' => "
			SELECT id
			FROM userTypes
			WHERE title = :title
			LIMIT 1",
		
		'getPrivacyLevels' => "
			SELECT id, title
			FROM privacyLevels",
		
		'getUserTypes' => "
			SELECT id, title
			FROM userTypes",
		
		'getJobTypes' => "
			SELECT id, title
			FROM jobTypes",
		
		'getRandomCategories' => "
			SELECT title
			FROM categories
			ORDER BY RAND()",
		
		'getRandomUsers' => "
			SELECT CONCAT_WS(' ',firstName,lastName) AS `name`
			FROM users
			ORDER BY RAND()",
		
		'getRandomRoles' => "
			SELECT title
			FROM userProjects
			ORDER BY RAND()",
		
		'getRandomCourses' => "
			SELECT id
			FROM courses
			ORDER BY RAND()",
			
			
		/**
		* Search queries
		*/
			
		'searchPeople' => "
			SELECT DISTINCT(u.id), firstName, lastName, avatar, vanityURL, bio, hometown, TIMESTAMPDIFF(YEAR, birthday, NOW()) AS age, ut.title AS `type`, ut.id as typeID,
			IF(
				TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) > 59,
				IF(
					TIMESTAMPDIFF(HOUR, `datetime`, NOW()) > 23,
					IF(
						TIMESTAMPDIFF(DAY, `datetime`, NOW()) > 30,
						DATE_FORMAT(`datetime`,'%M %D, %Y'),
						IF(
							TIMESTAMPDIFF(DAY, `datetime`, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(DAY, `datetime`, NOW()), 'days ago'),
							'yesterday'
						)
					),
					IF(
						TIMESTAMPDIFF(HOUR, `datetime`, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, NOW()), 'hours ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, `datetime`, NOW()), 'hour ago')
					)
				),
				IF(
					TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) = 0,
					'just now',
					IF(
						TIMESTAMPDIFF(MINUTE, `datetime`, NOW()) > 1,
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, NOW()), 'minutes ago'),
						CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, `datetime`, NOW()), 'minute ago')
					)
				)
			) AS `date`
			FROM users as u
			LEFT JOIN userTypes AS ut ON (u.typeID = ut.id)
			LEFT JOIN userLikes AS ul ON (u.id = ul.userID)
			LEFT JOIN categories AS c ON (c.id = ul.categoryID)
			WHERE firstName LIKE :term OR lastName LIKE :term OR CONCAT_WS(' ',firstName,lastName) LIKE :term OR vanityURL LIKE :term OR c.title LIKE :term",
		
		'searchProjects' => "
			SELECT *
			FROM (SELECT p.id, p.title, p.description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, u.firstName AS userFirstName, u.lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT pf.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 30,
							DATE_FORMAT(p.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, p.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, p.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, p.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, p.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, p.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM projects AS p
				LEFT JOIN users AS u ON (p.userID = u.id)
				LEFT JOIN userProjects AS up ON (p.id = up.projectID)
				LEFT JOIN users AS allu ON (allu.id = up.userID)
				LEFT JOIN projectCategories AS pc ON (p.id = pc.projectID)
				LEFT JOIN categories AS c ON (c.id = pc.categoryID)
				LEFT JOIN projectCourses AS pcour ON (p.id = pcour.projectID)
				LEFT JOIN courses AS cour ON (cour.id = pcour.courseID)
				LEFT JOIN projectFlags AS pf ON (p.id = pf.projectID)
				WHERE p.title LIKE :term OR p.description LIKE :term OR p.vanityURL LIKE :term OR c.title LIKE :term OR allu.firstName LIKE :term OR allu.lastName LIKE :term OR allu.vanityURL LIKE :term OR CONCAT_WS(' ',allu.firstName,allu.lastName) LIKE :term OR cour.id LIKE :term OR cour.title LIKE :term
				GROUP BY p.id) AS projects
			WHERE projects.flagCount < 3",
		
		'searchArticles' => "
			SELECT *
			FROM (SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, u.firstName AS userFirstName, u.lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT af.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 30,
							DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, a.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM articles AS a
				LEFT JOIN users AS u ON (a.userID = u.id)
				LEFT JOIN articleFlags AS af ON (a.id = af.articleID)
				LEFT JOIN userArticles AS ua ON (a.id = ua.articleID)
				LEFT JOIN users AS allu ON (allu.id = ua.userID)
				LEFT JOIN articleCategories AS ac ON (a.id = ac.articleID)
				LEFT JOIN categories AS c ON (c.id = ac.categoryID)
				LEFT JOIN articleCourses AS acour ON (a.id = acour.articleID)
				LEFT JOIN courses AS cour ON (cour.id = acour.courseID)
				WHERE a.title LIKE :term OR a.description LIKE :term OR a.vanityURL LIKE :term OR c.title LIKE :term OR allu.firstName LIKE :term OR allu.lastName LIKE :term OR allu.vanityURL LIKE :term OR CONCAT_WS(' ',allu.firstName,allu.lastName) LIKE :term OR cour.id LIKE :term OR cour.title LIKE :term
				GROUP BY a.id) AS articles
			WHERE articles.flagCount < 3",
		
		'searchPublicArticles' => "
			SELECT *
			FROM (SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, u.firstName AS userFirstName, u.lastName AS userLastName, u.avatar AS userAvatar, u.vanityURL AS userURL, COUNT(DISTINCT af.userID) AS flagCount,
				IF(
					TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 59,
					IF(
						TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 23,
						IF(
							TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 30,
							DATE_FORMAT(a.datetime,'%M %D, %Y at %l:%i %p'),
							IF(
								TIMESTAMPDIFF(DAY, a.datetime, NOW()) > 1,
								CONCAT_WS(' ', TIMESTAMPDIFF(DAY, a.datetime, NOW()), 'days ago'),
								'Yesterday'
							)
						),
						IF(
							TIMESTAMPDIFF(HOUR, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hours ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(HOUR, a.datetime, NOW()), 'hour ago')
						)
					),
					IF(
						TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) = 0,
						'Just now',
						IF(
							TIMESTAMPDIFF(MINUTE, a.datetime, NOW()) > 1,
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minutes ago'),
							CONCAT_WS(' ', TIMESTAMPDIFF(MINUTE, a.datetime, NOW()), 'minute ago')
						)
					)
				) AS `date`
				FROM articles AS a
				LEFT JOIN users AS u ON (a.userID = u.id)
				LEFT JOIN articleFlags AS af ON (a.id = af.articleID)
				LEFT JOIN userArticles AS ua ON (a.id = ua.articleID)
				LEFT JOIN users AS allu ON (allu.id = ua.userID)
				LEFT JOIN articleCategories AS ac ON (a.id = ac.articleID)
				LEFT JOIN categories AS c ON (c.id = ac.categoryID)
				LEFT JOIN articleCourses AS acour ON (a.id = acour.articleID)
				LEFT JOIN courses AS cour ON (cour.id = acour.courseID)
				LEFT JOIN privacyLevels AS pl ON (a.privacyLevelID = pl.id)
				WHERE pl.title = 'public' AND (a.title LIKE :term OR a.description LIKE :term OR a.vanityURL LIKE :term OR c.title LIKE :term OR allu.firstName LIKE :term OR allu.lastName LIKE :term OR allu.vanityURL LIKE :term OR CONCAT_WS(' ',allu.firstName,allu.lastName) LIKE :term OR cour.id LIKE :term OR cour.title LIKE :term)
				GROUP BY a.id) AS articles
			WHERE articles.flagCount < 3",
		
		'searchCourses' => "
			SELECT DISTINCT(c.id), c.title, c.description, `month`
			FROM courses AS c
			LEFT JOIN courseCategories AS cc ON (c.id = cc.courseID)
			LEFT JOIN categories AS cat ON (cat.id = cc.categoryID)
			WHERE c.id LIKE :term OR c.title LIKE :term OR c.description LIKE :term OR cat.title LIKE :term",
		
		'searchEvents' => "
			SELECT *
			FROM(SELECT e.id, e.userID, icsUID, e.title, e.description, vanityURL, 'event' AS `type`, location, e.datetime, startDateTime, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`, COUNT(DISTINCT ef.userID) AS flagCount
				FROM events AS e
				LEFT JOIN eventFlags AS ef ON (e.id = ef.eventID)
				LEFT JOIN eventCategories AS ec ON (e.id = ec.eventID)
				LEFT JOIN categories AS c ON (c.id = ec.categoryID)
				LEFT JOIN eventCourses AS ecour ON (e.id = ecour.eventID)
				LEFT JOIN courses AS cour ON (cour.id = ecour.courseID)
				WHERE TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) > -1 AND (e.title LIKE :term OR e.description LIKE :term OR location LIKE :term OR c.title LIKE :term OR cour.id LIKE :term OR cour.title LIKE :term)
				GROUP BY e.id) AS events
			WHERE events.flagCount < 3",
		
		'searchPublicEvents' => "
			SELECT *
			FROM(SELECT e.id, e.userID, icsUID, e.title, e.description, vanityURL, 'event' AS `type`, location, e.datetime, startDateTime, DATE_FORMAT(startDateTime,'%b') AS `month`, DATE_FORMAT(startDateTime,'%e') AS `day`, DATE_FORMAT(startDateTime,'%l:%i %p') AS `startTime`, DATE_FORMAT(endDateTime,'%l:%i %p') AS `endTime`, COUNT(DISTINCT ef.userID) AS flagCount
				FROM events AS e
				LEFT JOIN privacyLevels AS p ON (e.privacyLevelID = p.id)
				LEFT JOIN eventFlags AS ef ON (e.id = ef.eventID)
				LEFT JOIN eventCategories AS ec ON (e.id = ec.eventID)
				LEFT JOIN categories AS c ON (c.id = ec.categoryID)
				LEFT JOIN eventCourses AS ecour ON (e.id = ecour.eventID)
				LEFT JOIN courses AS cour ON (cour.id = ecour.courseID)
				WHERE p.title = 'Public' AND TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) > -1 AND (e.title LIKE :term OR e.description LIKE :term OR location LIKE :term OR c.title LIKE :term OR cour.id LIKE :term OR cour.title LIKE :term)
				GROUP BY e.id) AS events
			WHERE events.flagCount < 3",
			
			
		/**
		* Messaging queries
		*/
			
		'getUnreadMessageCount' => "
			SELECT COUNT(*) AS messageCount
			FROM messages AS m
			LEFT JOIN messageStatuses AS ms ON (m.status = ms.id)
			WHERE toUserID = 1 AND ms.title = 'unread'"
	
						
	);
	
	public function __get($id){
		return $this->_info[$id];
	}
	
	public function __construct(){
		
	}
	
	public function __destruct(){
		
	}
}