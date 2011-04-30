<?php

namespace WDDSocial;

/**
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class UserSession {
	
	
	private static $_error_message;
	
	
	
	/**
	* Initialize session
	*/
	
	public static function init() {
		session_start();
		static::fake_user(); #tmp
	}
	
	
	
	/**
	* Process user signin
	*/
	
	public static function signin($email, $password) {
		
		# validate input
		if (!isset($email) or empty($email)) {
			
		}
		if (!isset($password) or empty($password)) {
		
		}
		
		# query database
		
		
		return true;
	}
	
	
	
	/**
	* 
	*/
	
	public static function fake_user() {
		
		# get user information
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->prepare($sql->getUserByID);
		import('wddsocial.model.WDDSocial\UserVO');
		$query->setFetchMode(\PDO::FETCH_CLASS, 'WDDSocial\UserVO');
		$data = array('id' => 1);
		$query->execute($data);
		$user = $query->fetch();
		
		# set session
		$_SESSION['user'] = $user;
		$_SESSION['authorized'] = false;
	}
	
	
	
	/**
	* Checks if a user is the currently signed in user
	*/
	
	public static function is_current($userID){
		return ($userID == $_SESSION['user']->id)?true:false;
	}
	
	
	
	/**
	* Checks if the current user is authorized
	*/
	
	public static function is_authorized(){
		return ($_SESSION['authorized'] == true)?true:false;
	}
}