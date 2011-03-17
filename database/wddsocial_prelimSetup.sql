# Sequel Pro dump
# Version 2492
# http://code.google.com/p/sequel-pro
#
# Host: localhost (MySQL 5.1.44)
# Database: wddsocial
# Generation Time: 2011-03-11 00:04:46 -0500
# ************************************************************

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table articleComments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articleComments`;

CREATE TABLE `articleComments` (
  `articleID` int(11) DEFAULT NULL,
  `commentID` int(11) DEFAULT NULL,
  KEY `articleID` (`articleID`),
  KEY `commentID` (`commentID`),
  CONSTRAINT `articleComments_ibfk_2` FOREIGN KEY (`commentID`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articleComments_ibfk_1` FOREIGN KEY (`articleID`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table articles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articles`;

CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `content` text,
  `link` varchar(255) DEFAULT NULL,
  `privacyLevelID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `privacyLevelID` (`privacyLevelID`),
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`privacyLevelID`) REFERENCES `privacyLevels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table articleTechnologies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articleTechnologies`;

CREATE TABLE `articleTechnologies` (
  `articleID` int(11) DEFAULT NULL,
  `technologyID` int(11) DEFAULT NULL,
  KEY `articleID` (`articleID`),
  KEY `technologyID` (`technologyID`),
  CONSTRAINT `articleTechnologies_ibfk_2` FOREIGN KEY (`technologyID`) REFERENCES `technologies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `articleTechnologies_ibfk_1` FOREIGN KEY (`articleID`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
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


# Dump of table courseTechnologies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `courseTechnologies`;

CREATE TABLE `courseTechnologies` (
  `courseID` varchar(4) DEFAULT NULL,
  `technologyID` int(11) DEFAULT NULL,
  KEY `courseID` (`courseID`),
  KEY `technologyID` (`technologyID`),
  CONSTRAINT `courseTechnologies_ibfk_2` FOREIGN KEY (`technologyID`) REFERENCES `technologies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `courseTechnologies_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



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


# Dump of table eventComments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eventComments`;

CREATE TABLE `eventComments` (
  `eventID` int(11) DEFAULT NULL,
  `commentID` int(11) DEFAULT NULL,
  KEY `eventID` (`eventID`),
  KEY `commentID` (`commentID`),
  CONSTRAINT `eventComments_ibfk_2` FOREIGN KEY (`commentID`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `eventComments_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `privacyLevelID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `privacyLevelID` (`privacyLevelID`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`privacyLevelID`) REFERENCES `privacyLevels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table eventTechnologies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eventTechnologies`;

CREATE TABLE `eventTechnologies` (
  `eventID` int(11) DEFAULT NULL,
  `technologyID` int(11) DEFAULT NULL,
  KEY `eventID` (`eventID`),
  KEY `technologyID` (`technologyID`),
  CONSTRAINT `eventTechnologies_ibfk_2` FOREIGN KEY (`technologyID`) REFERENCES `technologies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `eventTechnologies_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeID` int(11) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `content` text,
  `location` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `typeID` (`typeID`),
  CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`typeID`) REFERENCES `jobTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table jobTechnologies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobTechnologies`;

CREATE TABLE `jobTechnologies` (
  `jobID` int(11) DEFAULT NULL,
  `technologyID` int(11) DEFAULT NULL,
  KEY `jobID` (`jobID`),
  KEY `technologyID` (`technologyID`),
  CONSTRAINT `jobTechnologies_ibfk_2` FOREIGN KEY (`technologyID`) REFERENCES `technologies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jobTechnologies_ibfk_1` FOREIGN KEY (`jobID`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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


# Dump of table privacyLevels
# ------------------------------------------------------------

DROP TABLE IF EXISTS `privacyLevels`;

CREATE TABLE `privacyLevels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) DEFAULT NULL,
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


# Dump of table projectComments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projectComments`;

CREATE TABLE `projectComments` (
  `projectID` int(11) DEFAULT NULL,
  `commentID` int(11) DEFAULT NULL,
  KEY `projectID` (`projectID`),
  KEY `commentID` (`commentID`),
  CONSTRAINT `projectComments_ibfk_2` FOREIGN KEY (`commentID`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectComments_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table projectCourses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projectCourses`;

CREATE TABLE `projectCourses` (
  `projectID` int(11) DEFAULT NULL,
  `courseID` varchar(4) DEFAULT NULL,
  KEY `projectID` (`projectID`),
  KEY `courseID` (`courseID`),
  CONSTRAINT `projectCourses_ibfk_2` FOREIGN KEY (`courseID`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectCourses_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table projects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  `content` text,
  `vanityURL` varchar(64) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table projectTechnologies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projectTechnologies`;

CREATE TABLE `projectTechnologies` (
  `projectID` int(11) DEFAULT NULL,
  `technologyID` int(11) DEFAULT NULL,
  KEY `projectID` (`projectID`),
  KEY `technologyID` (`technologyID`),
  CONSTRAINT `projectTechnologies_ibfk_2` FOREIGN KEY (`technologyID`) REFERENCES `technologies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectTechnologies_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table technologies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `technologies`;

CREATE TABLE `technologies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table userArticles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userArticles`;

CREATE TABLE `userArticles` (
  `userID` int(11) DEFAULT NULL,
  `articleID` int(11) DEFAULT NULL,
  KEY `userID` (`userID`),
  KEY `articleID` (`articleID`),
  CONSTRAINT `userArticles_ibfk_2` FOREIGN KEY (`articleID`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userArticles_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table userAvailable
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userAvailable`;

CREATE TABLE `userAvailable` (
  `userID` int(11) DEFAULT NULL,
  `technologyID` int(11) DEFAULT NULL,
  KEY `userID` (`userID`),
  KEY `technologyID` (`technologyID`),
  CONSTRAINT `userAvailable_ibfk_2` FOREIGN KEY (`technologyID`) REFERENCES `technologies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userAvailable_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table userDislikes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userDislikes`;

CREATE TABLE `userDislikes` (
  `userID` int(11) DEFAULT NULL,
  `technologyID` int(11) DEFAULT NULL,
  KEY `userID` (`userID`),
  KEY `technologyID` (`technologyID`),
  CONSTRAINT `userDislikes_ibfk_2` FOREIGN KEY (`technologyID`) REFERENCES `technologies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userDislikes_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table userLikes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userLikes`;

CREATE TABLE `userLikes` (
  `userID` int(11) DEFAULT NULL,
  `technologyID` int(11) DEFAULT NULL,
  KEY `userID` (`userID`),
  KEY `technologyID` (`technologyID`),
  CONSTRAINT `userLikes_ibfk_2` FOREIGN KEY (`technologyID`) REFERENCES `technologies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userLikes_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table userProjects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userProjects`;

CREATE TABLE `userProjects` (
  `userID` int(11) DEFAULT NULL,
  `projectID` int(11) DEFAULT NULL,
  KEY `projectID` (`projectID`),
  KEY `userID` (`userID`),
  CONSTRAINT `userProjects_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `userProjects_ibfk_1` FOREIGN KEY (`projectID`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeID` int(11) DEFAULT NULL,
  `firstName` varchar(32) DEFAULT NULL,
  `lastName` varchar(32) DEFAULT NULL,
  `contactEmail` varchar(64) DEFAULT NULL,
  `fullsailEmail` varchar(64) DEFAULT NULL,
  `username` varchar(64) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `hometown` varchar(128) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `joinDate` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `typeID` (`typeID`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`typeID`) REFERENCES `userTypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table userTypes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userTypes`;

CREATE TABLE `userTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) DEFAULT NULL,
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





/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
