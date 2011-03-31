# Sequel Pro dump
# Version 2492
# http://code.google.com/p/sequel-pro
#
# Host: external-db.s112587.gridserver.com (MySQL 5.1.55-rel12.6)
# Database: db112587_wddsocial
# Generation Time: 2011-03-30 23:18:55 -0400
# ************************************************************

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table alumDetail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `alumDetail`;

CREATE TABLE `alumDetail` (
  `userID` int(11) DEFAULT NULL,
  `graduationDate` date DEFAULT NULL,
  `employerTitle` varchar(32) DEFAULT NULL,
  `employerLink` varchar(64) DEFAULT NULL,
  KEY `userID` (`userID`),
  CONSTRAINT `alumDetail_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table articleCategories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articleCategories`;

CREATE TABLE `articleCategories` (
  `articleID` int(11) DEFAULT NULL,
  `categoryID` int(11) DEFAULT NULL,
  KEY `articleID` (`articleID`),
  KEY `categoryID` (`categoryID`),
  CONSTRAINT `articleCategories_ibfk_1` FOREIGN KEY (`articleID`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articleCategories_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table articleComments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articleComments`;

CREATE TABLE `articleComments` (
  `articleID` int(11) DEFAULT NULL,
  `commentID` int(11) DEFAULT NULL,
  KEY `articleID` (`articleID`),
  KEY `commentID` (`commentID`),
  CONSTRAINT `articleComments_ibfk_1` FOREIGN KEY (`articleID`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articleComments_ibfk_2` FOREIGN KEY (`commentID`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table articleCourses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articleCourses`;

CREATE TABLE `articleCourses` (
  `articleID` int(11) DEFAULT NULL,
  `courseID` varchar(4) DEFAULT NULL,
  KEY `articleID` (`articleID`),
  KEY `courseID` (`courseID`),
  CONSTRAINT `articleCourses_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articleCourses_ibfk_1` FOREIGN KEY (`articleID`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table articleFlags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articleFlags`;

CREATE TABLE `articleFlags` (
  `articleID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  KEY `articleID` (`articleID`),
  KEY `userID` (`userID`),
  CONSTRAINT `articleFlags_ibfk_1` FOREIGN KEY (`articleID`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articleFlags_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table articleImages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articleImages`;

CREATE TABLE `articleImages` (
  `articleID` int(11) DEFAULT NULL,
  `imageID` int(11) DEFAULT NULL,
  KEY `articleID` (`articleID`),
  KEY `imageID` (`imageID`),
  CONSTRAINT `articleImages_ibfk_1` FOREIGN KEY (`articleID`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articleImages_ibfk_2` FOREIGN KEY (`imageID`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table articleLinks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articleLinks`;

CREATE TABLE `articleLinks` (
  `articleID` int(11) DEFAULT NULL,
  `linkID` int(11) DEFAULT NULL,
  KEY `articleID` (`articleID`),
  KEY `linkID` (`linkID`),
  CONSTRAINT `articleLinks_ibfk_1` FOREIGN KEY (`articleID`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articleLinks_ibfk_2` FOREIGN KEY (`linkID`) REFERENCES `links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table articles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articles`;

CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `privacyLevelID` int(11) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `content` text,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `privacyLevelID` (`privacyLevelID`),
  KEY `userID` (`userID`),
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`privacyLevelID`) REFERENCES `privacyLevels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table articleVideos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articleVideos`;

CREATE TABLE `articleVideos` (
  `articleID` int(11) DEFAULT NULL,
  `videoID` int(11) DEFAULT NULL,
  KEY `articleID` (`articleID`),
  KEY `videoID` (`videoID`),
  CONSTRAINT `articleVideos_ibfk_1` FOREIGN KEY (`articleID`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articleVideos_ibfk_2` FOREIGN KEY (`videoID`) REFERENCES `videos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table commentFlags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `commentFlags`;

CREATE TABLE `commentFlags` (
  `commentID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  KEY `commentID` (`commentID`),
  KEY `userID` (`userID`),
  CONSTRAINT `commentFlags_ibfk_1` FOREIGN KEY (`commentID`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `commentFlags_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `content` text,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table courseCategories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `courseCategories`;

CREATE TABLE `courseCategories` (
  `courseID` varchar(4) DEFAULT NULL,
  `categoryID` int(11) DEFAULT NULL,
  KEY `courseID` (`courseID`),
  KEY `categoryID` (`categoryID`),
  CONSTRAINT `courseCategories_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `courseCategories_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table courseLinks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `courseLinks`;

CREATE TABLE `courseLinks` (
  `courseID` varchar(4) DEFAULT NULL,
  `linkID` int(11) DEFAULT NULL,
  KEY `courseID` (`courseID`),
  KEY `linkID` (`linkID`),
  CONSTRAINT `courseLinks_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `courseLinks_ibfk_2` FOREIGN KEY (`linkID`) REFERENCES `links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table courses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `courses`;

CREATE TABLE `courses` (
  `id` varchar(4) NOT NULL,
  `degreeID` varchar(3) DEFAULT NULL,
  `title` varchar(48) DEFAULT NULL,
  `month` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `degreeID` (`degreeID`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`degreeID`) REFERENCES `degrees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` (`id`,`degreeID`,`title`,`month`)
VALUES
	('ADB','WDD','Advanced Database Structures',15),
	('ADG','WDD','Advanced Computer Graphics',3),
	('ADT','WDD','Applied Design Tools and Interfaces',5),
	('ASL','WDD','Advanced Server-Side Languages',15),
	('DBS','WDD','Database Structures',14),
	('DCG','WDD','Designing Computer Graphics',2),
	('DFP','WDD','Deployment of Flash Projects',11),
	('DWS','WDD','Designing for Web Standards',6),
	('FAT','WDD','Flash ActionScript Techniques',10),
	('FDA','WDD','Flash Design and Animation',9),
	('FFM','WDD','Flash Fundamentals',8),
	('FFW','WDD','Flex Frameworks',12),
	('MDD','WDD','Mobile Device Deployment',18),
	('NSS','WDD','Networks and Server Structures',4),
	('OOP','WDD','Concepts of Object Oriented Programming',9),
	('PPP','WDD','Principles of Production Process',5),
	('RMO','WDD','Rich Media Optimization',16),
	('SFW1','WDD','Scripting for Web Applications 1',12),
	('SFW2','WDD','Scripting for Web Applications 2',13),
	('SMS','WDD','Streaming Media Servers',17),
	('SSL','WDD','Server-side Languages',14),
	('WDF','WDD','Web Design Fundamentals',6),
	('WFP1','WDD','Web Final Project I',20),
	('WFP2','WDD','Web Final Project II',21),
	('WIU','WDD','Web Interface and Usability',4),
	('WPP','WDD','Web Project Preproduction',19),
	('WSP','WDD','Web Standards Project',7);

/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table degrees
# ------------------------------------------------------------

DROP TABLE IF EXISTS `degrees`;

CREATE TABLE `degrees` (
  `id` varchar(3) NOT NULL,
  `title` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `degrees` WRITE;
/*!40000 ALTER TABLE `degrees` DISABLE KEYS */;
INSERT INTO `degrees` (`id`,`title`)
VALUES
	('WDD','Web Design and Development');

/*!40000 ALTER TABLE `degrees` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table eventCategories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eventCategories`;

CREATE TABLE `eventCategories` (
  `eventID` int(11) DEFAULT NULL,
  `categoryID` int(11) DEFAULT NULL,
  KEY `eventID` (`eventID`),
  KEY `categoryID` (`categoryID`),
  CONSTRAINT `eventCategories_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `eventCategories_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table eventComments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eventComments`;

CREATE TABLE `eventComments` (
  `eventID` int(11) DEFAULT NULL,
  `commentID` int(11) DEFAULT NULL,
  KEY `eventID` (`eventID`),
  KEY `commentID` (`commentID`),
  CONSTRAINT `eventComments_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `eventComments_ibfk_2` FOREIGN KEY (`commentID`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table eventCourses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eventCourses`;

CREATE TABLE `eventCourses` (
  `eventID` int(11) DEFAULT NULL,
  `courseID` varchar(4) DEFAULT NULL,
  KEY `eventID` (`eventID`),
  KEY `courseID` (`courseID`),
  CONSTRAINT `eventCourses_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `eventCourses_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table eventFlags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eventFlags`;

CREATE TABLE `eventFlags` (
  `eventID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  KEY `eventID` (`eventID`),
  KEY `userID` (`userID`),
  CONSTRAINT `eventFlags_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `eventFlags_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table eventImages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eventImages`;

CREATE TABLE `eventImages` (
  `eventID` int(11) DEFAULT NULL,
  `imageID` int(11) DEFAULT NULL,
  KEY `eventID` (`eventID`),
  KEY `imageID` (`imageID`),
  CONSTRAINT `eventImages_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `eventImages_ibfk_2` FOREIGN KEY (`imageID`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table eventLinks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eventLinks`;

CREATE TABLE `eventLinks` (
  `eventID` int(11) DEFAULT NULL,
  `linkID` int(11) DEFAULT NULL,
  KEY `eventID` (`eventID`),
  KEY `linkID` (`linkID`),
  CONSTRAINT `eventLinks_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `eventLinks_ibfk_2` FOREIGN KEY (`linkID`) REFERENCES `links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `privacyLevelID` int(11) DEFAULT NULL,
  `icsUID` varchar(32) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `startDatetime` datetime DEFAULT NULL,
  `endDatetime` datetime DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `privacyLevelID` (`privacyLevelID`),
  KEY `userID` (`userID`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`privacyLevelID`) REFERENCES `privacyLevels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `events_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` (`id`,`userID`,`privacyLevelID`,`icsUID`,`title`,`description`,`location`,`startDatetime`,`endDatetime`,`datetime`)
VALUES
	(1,NULL,1,'c3011c3f428816836887a5ef90a9027b','Web Final Presentations','Compellingly pursue cross functional metrics with client-focused networks. Proactively implement high-payoff methods of empowerment.','FS3B - Auditorium','2011-03-31 11:00:00','2011-03-31 13:00:00','2011-03-22 11:57:40');

/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table eventVideos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eventVideos`;

CREATE TABLE `eventVideos` (
  `eventID` int(11) DEFAULT NULL,
  `videoID` int(11) DEFAULT NULL,
  KEY `eventID` (`eventID`),
  KEY `videoID` (`videoID`),
  CONSTRAINT `eventVideos_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `eventVideos_ibfk_2` FOREIGN KEY (`videoID`) REFERENCES `videos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table imageFlags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `imageFlags`;

CREATE TABLE `imageFlags` (
  `imageID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  KEY `imageID` (`imageID`),
  KEY `userID` (`userID`),
  CONSTRAINT `imageFlags_ibfk_1` FOREIGN KEY (`imageID`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `imageFlags_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table images
# ------------------------------------------------------------

DROP TABLE IF EXISTS `images`;

CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `title` varchar(32) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `location` varchar(128) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table jobCategories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobCategories`;

CREATE TABLE `jobCategories` (
  `jobID` int(11) DEFAULT NULL,
  `categoryID` int(11) DEFAULT NULL,
  KEY `jobID` (`jobID`),
  KEY `categoryID` (`categoryID`),
  CONSTRAINT `jobCategories_ibfk_1` FOREIGN KEY (`jobID`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jobCategories_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table jobFlags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobFlags`;

CREATE TABLE `jobFlags` (
  `jobID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  KEY `jobID` (`jobID`),
  KEY `userID` (`userID`),
  CONSTRAINT `jobFlags_ibfk_1` FOREIGN KEY (`jobID`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jobFlags_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table jobImages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobImages`;

CREATE TABLE `jobImages` (
  `jobID` int(11) DEFAULT NULL,
  `imageID` int(11) DEFAULT NULL,
  KEY `jobID` (`jobID`),
  KEY `imageID` (`imageID`),
  CONSTRAINT `jobImages_ibfk_1` FOREIGN KEY (`jobID`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jobImages_ibfk_2` FOREIGN KEY (`imageID`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table jobLinks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobLinks`;

CREATE TABLE `jobLinks` (
  `jobID` int(11) DEFAULT NULL,
  `linkID` int(11) DEFAULT NULL,
  KEY `jobID` (`jobID`),
  KEY `linkID` (`linkID`),
  CONSTRAINT `jobLinks_ibfk_1` FOREIGN KEY (`jobID`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jobLinks_ibfk_2` FOREIGN KEY (`linkID`) REFERENCES `links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `typeID` int(11) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `compensation` varchar(128) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `content` text,
  `email` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `typeID` (`typeID`),
  KEY `userID` (`userID`),
  CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`typeID`) REFERENCES `jobTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table jobTypes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobTypes`;

CREATE TABLE `jobTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

LOCK TABLES `jobTypes` WRITE;
/*!40000 ALTER TABLE `jobTypes` DISABLE KEYS */;
INSERT INTO `jobTypes` (`id`,`title`)
VALUES
	(1,'Full-Time'),
	(2,'Contract'),
	(3,'Freelance'),
	(4,'Internship');

/*!40000 ALTER TABLE `jobTypes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table jobVideos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobVideos`;

CREATE TABLE `jobVideos` (
  `jobID` int(11) DEFAULT NULL,
  `videoID` int(11) DEFAULT NULL,
  KEY `jobID` (`jobID`),
  KEY `videoID` (`videoID`),
  CONSTRAINT `jobVideos_ibfk_1` FOREIGN KEY (`jobID`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jobVideos_ibfk_2` FOREIGN KEY (`videoID`) REFERENCES `videos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table linkFlags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `linkFlags`;

CREATE TABLE `linkFlags` (
  `linkID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  KEY `linkID` (`linkID`),
  KEY `userID` (`userID`),
  CONSTRAINT `linkFlags_ibfk_1` FOREIGN KEY (`linkID`) REFERENCES `links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `linkFlags_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table links
# ------------------------------------------------------------

DROP TABLE IF EXISTS `links`;

CREATE TABLE `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) DEFAULT NULL,
  `link` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table messages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `toUserID` int(11) DEFAULT NULL,
  `fromUserID` int(11) DEFAULT NULL,
  `content` text,
  `datetime` datetime DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `toUserID` (`toUserID`),
  KEY `fromUserID` (`fromUserID`),
  KEY `status` (`status`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`toUserID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`fromUserID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`status`) REFERENCES `messageStatuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table messageStatuses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `messageStatuses`;

CREATE TABLE `messageStatuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

LOCK TABLES `messageStatuses` WRITE;
/*!40000 ALTER TABLE `messageStatuses` DISABLE KEYS */;
INSERT INTO `messageStatuses` (`id`,`title`)
VALUES
	(1,'Unread'),
	(2,'Read'),
	(3,'Deleted');

/*!40000 ALTER TABLE `messageStatuses` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table privacyLevels
# ------------------------------------------------------------

DROP TABLE IF EXISTS `privacyLevels`;

CREATE TABLE `privacyLevels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

LOCK TABLES `privacyLevels` WRITE;
/*!40000 ALTER TABLE `privacyLevels` DISABLE KEYS */;
INSERT INTO `privacyLevels` (`id`,`title`)
VALUES
	(1,'Public'),
	(2,'Private');

/*!40000 ALTER TABLE `privacyLevels` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table projectCategories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projectCategories`;

CREATE TABLE `projectCategories` (
  `projectID` int(11) DEFAULT NULL,
  `categoryID` int(11) DEFAULT NULL,
  KEY `projectID` (`projectID`),
  KEY `categoryID` (`categoryID`),
  CONSTRAINT `projectCategories_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectCategories_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table projectComments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projectComments`;

CREATE TABLE `projectComments` (
  `projectID` int(11) DEFAULT NULL,
  `commentID` int(11) DEFAULT NULL,
  KEY `projectID` (`projectID`),
  KEY `commentID` (`commentID`),
  CONSTRAINT `projectComments_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectComments_ibfk_2` FOREIGN KEY (`commentID`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table projectCourses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projectCourses`;

CREATE TABLE `projectCourses` (
  `projectID` int(11) DEFAULT NULL,
  `courseID` varchar(4) DEFAULT NULL,
  KEY `projectID` (`projectID`),
  KEY `courseID` (`courseID`),
  CONSTRAINT `projectCourses_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectCourses_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table projectFlags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projectFlags`;

CREATE TABLE `projectFlags` (
  `projectID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  KEY `projectID` (`projectID`),
  KEY `userID` (`userID`),
  CONSTRAINT `projectFlags_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectFlags_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table projectImages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projectImages`;

CREATE TABLE `projectImages` (
  `projectID` int(11) DEFAULT NULL,
  `imageID` int(11) DEFAULT NULL,
  KEY `projectID` (`projectID`),
  KEY `imageID` (`imageID`),
  CONSTRAINT `projectImages_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectImages_ibfk_2` FOREIGN KEY (`imageID`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table projectLinks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projectLinks`;

CREATE TABLE `projectLinks` (
  `projectID` int(11) DEFAULT NULL,
  `linkID` int(11) DEFAULT NULL,
  KEY `projectID` (`projectID`),
  KEY `linkID` (`linkID`),
  CONSTRAINT `projectLinks_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectLinks_ibfk_2` FOREIGN KEY (`linkID`) REFERENCES `links` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table projects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `content` text,
  `vanityURL` varchar(64) DEFAULT NULL,
  `completeDate` date DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table projectVideos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projectVideos`;

CREATE TABLE `projectVideos` (
  `projectID` int(11) DEFAULT NULL,
  `videoID` int(11) DEFAULT NULL,
  KEY `projectID` (`projectID`),
  KEY `videoID` (`videoID`),
  CONSTRAINT `projectVideos_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectVideos_ibfk_2` FOREIGN KEY (`videoID`) REFERENCES `videos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table studentDetail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `studentDetail`;

CREATE TABLE `studentDetail` (
  `userID` int(11) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `location` enum('campus','online') DEFAULT NULL,
  KEY `userID` (`userID`),
  CONSTRAINT `studentDetail_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table teacherCourses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teacherCourses`;

CREATE TABLE `teacherCourses` (
  `userID` int(11) DEFAULT NULL,
  `courseID` varchar(4) DEFAULT NULL,
  KEY `userID` (`userID`),
  KEY `courseID` (`courseID`),
  CONSTRAINT `teacherCourses_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `teacherCourses_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table userArticles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userArticles`;

CREATE TABLE `userArticles` (
  `userID` int(11) DEFAULT NULL,
  `articleID` int(11) DEFAULT NULL,
  KEY `userID` (`userID`),
  KEY `articleID` (`articleID`),
  CONSTRAINT `userArticles_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userArticles_ibfk_2` FOREIGN KEY (`articleID`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table userDislikes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userDislikes`;

CREATE TABLE `userDislikes` (
  `userID` int(11) DEFAULT NULL,
  `categoryID` int(11) DEFAULT NULL,
  KEY `userID` (`userID`),
  KEY `categoryID` (`categoryID`),
  CONSTRAINT `userDislikes_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userDislikes_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table userLikes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userLikes`;

CREATE TABLE `userLikes` (
  `userID` int(11) DEFAULT NULL,
  `categoryID` int(11) DEFAULT NULL,
  KEY `userID` (`userID`),
  KEY `categoryID` (`categoryID`),
  CONSTRAINT `userLikes_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userLikes_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table userProjects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userProjects`;

CREATE TABLE `userProjects` (
  `userID` int(11) DEFAULT NULL,
  `projectID` int(11) DEFAULT NULL,
  `title` varchar(64) DEFAULT NULL,
  KEY `projectID` (`projectID`),
  KEY `userID` (`userID`),
  CONSTRAINT `userProjects_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userProjects_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeID` int(11) DEFAULT NULL,
  `firstName` varchar(32) DEFAULT NULL,
  `lastName` varchar(32) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `fullsailEmail` varchar(64) DEFAULT NULL,
  `vanityURL` varchar(64) DEFAULT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `hometown` varchar(128) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `website` varchar(64) DEFAULT NULL,
  `twitter` varchar(64) DEFAULT NULL,
  `facebook` varchar(64) DEFAULT NULL,
  `github` varchar(64) DEFAULT NULL,
  `dribbble` varchar(64) DEFAULT NULL,
  `forrst` varchar(64) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `typeID` (`typeID`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`typeID`) REFERENCES `userTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`,`typeID`,`firstName`,`lastName`,`email`,`password`,`fullsailEmail`,`vanityURL`,`bio`,`hometown`,`birthday`,`website`,`twitter`,`facebook`,`github`,`dribbble`,`forrst`,`datetime`)
VALUES
	(1,1,'Anthony','Colangelo','me@acolangelo.com','1a1dc91c907325c69271ddf0c944bc72','acolangelo@fullsail.edu','anthony',NULL,'Mount Laurel, NJ','1991-02-13',NULL,NULL,NULL,NULL,NULL,NULL,'2011-03-10 00:00:00');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table userTypes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userTypes`;

CREATE TABLE `userTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

LOCK TABLES `userTypes` WRITE;
/*!40000 ALTER TABLE `userTypes` DISABLE KEYS */;
INSERT INTO `userTypes` (`id`,`title`)
VALUES
	(1,'Student'),
	(2,'Teacher'),
	(3,'Alum');

/*!40000 ALTER TABLE `userTypes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table videoFlags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `videoFlags`;

CREATE TABLE `videoFlags` (
  `videoID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  KEY `videoID` (`videoID`),
  KEY `userID` (`userID`),
  CONSTRAINT `videoFlags_ibfk_1` FOREIGN KEY (`videoID`) REFERENCES `videos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `videoFlags_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table videos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `videos`;

CREATE TABLE `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `embedCode` varchar(255) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;






/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
