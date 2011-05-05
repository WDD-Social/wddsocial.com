<?php

namespace WDDSocial;

/**
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class UserSession {
	
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
		$query = $db->prepare($sql->getUserByLogin);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$data = array('email' => $email, 'password' => $password);
		$query->execute($data);
		
		if ($query->rowCount() == 0)
			return false;
				
		# set session
		$_SESSION['user'] = $query->fetch();
		$_SESSION['authorized'] = true;
		
		return true;

	}
	
	
	
	/**
	* Signs a user out and destroys session data
	*/
	
	public static function signout() {
		$_SESSION['user'] = null;
		$_SESSION['authorized'] = false;
	}
	
	
	
	/**
	* Checks if a user is the currently signed in user
	*/
	
	public static function is_current($userID){
		if ($userID == $_SESSION['user']->id) return true;
		else return false;
	}
	
	
	
	/**
	* Checks if the current user is authorized
	*/
	
	public static function is_authorized(){
		if ($_SESSION['authorized'] and isset($_SESSION['user'])) return true;
		else return false;
	}
	
	
	
	/**
	* Protects a page from an unauthorized user
	*/
	
	public static function protect() {
		if (!static::is_authorized()) {
			$_SESSION['last_page'] = \Framework5\Request::uri();
			redirect('/signin');
		}
	}
}