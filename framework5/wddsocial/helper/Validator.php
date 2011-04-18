<?php

namespace WDDSocial;
/*
*
* @author: Anthony Colangelo (me@acolangelo.com)
*
*/
class Validator{
	public static function isCurrentUser($userID){
		return ($userID == $_SESSION['user']->id)?true:false;
	}
}