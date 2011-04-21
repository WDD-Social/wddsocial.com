<?php

namespace WDDSocial;
/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class RecentPersonVO{
	
	public $contentID, $contentTitle, $contentVanityURL, $userID, $userFirstName, $userLastName, $userAvatar, $userVanityURL, $datetime, $date, $type;
	
	
	/* SELECT c.id AS contentID, '' AS contentTitle, '' AS contentVanityURL, u.id AS userID, firstName AS userFirstName, lastName AS userLastName, avatar AS userAvatar, u.vanityURL AS userVanityURL, c.datetime AS `datetime`, getDateDiffEN(c.datetime) AS `date`, 'comment' AS `type` */

}