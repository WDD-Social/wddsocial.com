<?php

namespace WDDSocial;
/*
*
* @author: Anthony Colangelo (me@acolangelo.com)
*
*/
class UserValidator{

	# CHECKS IF A USER IS THE CURRENTLY SIGNED IN USER
	public static function is_current($userID){
		return ($userID == $_SESSION['user']->id)?true:false;
	}
	
	
	
	# CHECKS IF THE CURRENT USER IS AUTHORIZED
	public static function is_authorized(){
		return ($_SESSION['user'] == true)?true:false;
	}
}