<?php

namespace WDDSocial;
/*
*
* @author: Anthony Colangelo (me@acolangelo.com)
*
*/
class UserValidator{
	public static function is_current($userID){
		return ($userID == $_SESSION['user']->id)?true:false;
	}
	
	public static function is_authorized(){
		return ($_SESSION['user'] == true)?true:false;
	}
}