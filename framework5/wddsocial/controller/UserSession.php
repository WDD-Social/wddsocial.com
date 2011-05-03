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
	}
	
	
	
	/**
	* Process user signin
	*/
	
	public static function signin($email, $password) {
		
		# validate input
		if (!isset($email) or empty($email))
			return false;
		if (!isset($password) or empty($password))
			return false;
		
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		# check login information
		$query = $db->prepare($sql->getUserIDByLogin);
		import('wddsocial.model.WDDSocial\UserVO');
		$query->setFetchMode(\PDO::FETCH_CLASS, 'WDDSocial\UserVO');
		$data = array('email' => $email, 'password' => $password);
		$query->execute($data);
		$user_id = $query->fetch();
		
		# get user info for session
		$query = $db->prepare($sql->getUserByID);
		import('wddsocial.model.WDDSocial\UserVO');
		$query->setFetchMode(\PDO::FETCH_CLASS, 'WDDSocial\UserVO');
		$data = array('id' => $id);
		$query->execute($data);
		$user = $query->fetch();
		
		# set session
		$_SESSION['user'] = $user;
		$_SESSION['authorized'] = true;
		
		return true;

	}
	
	
	
	/**
	* Signs a user out, destroys session data
	*/
	
	public static function signout() {
		$_SESSION['user'] = NULL;
		$_SESSION['authorized'] = false;
	}
	
	
	
	public static function fake_user_signin($id){
		
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		# get user info for session
		$query = $db->prepare($sql->getUserByID);
		import('wddsocial.model.WDDSocial\UserVO');
		$query->setFetchMode(\PDO::FETCH_CLASS, 'WDDSocial\UserVO');
		$data = array('id' => $id);
		$query->execute($data);
		$user = $query->fetch();
		
		# set session
		$_SESSION['user'] = $user;
		$_SESSION['authorized'] = true;
		
		return true;
	}
	
	public static function fake_user_signout() {
		$_SESSION['user'] = NULL;
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