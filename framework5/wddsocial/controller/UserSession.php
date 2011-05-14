<?php

namespace WDDSocial;

/**
* Handles a user session, including signin and signout
* 
* @author tmatthews (tmatthewsdev@gmail.com)
* @author Anthony Colangelo (me@acolangelo.com) 
*/

class UserSession {
	
	private static $_init = false; # is the session started?
	
	/**
	* Initialize session
	*/
	
	public static function init() {
		session_start();
		static::$_init = true;
		return true;
	}
	
	
	
	/**
	* Process user signin
	*/
	
	public static function signin($email, $password) {
		
		static::session_started();
		
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
		static::session_started();
		$_SESSION['user'] = null;
		$_SESSION['authorized'] = false;
	}
	
	
	
	/**
	* Refreshes user's data (used when updating)
	*/
	
	public static function refresh() {
		static::session_started();
		if ($_SESSION['user'] != null) {
			$db = instance(':db');
			$sql = instance(':sel-sql');
	
			$query = $db->prepare($sql->getUserSessionDataByID);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$data = array('id' => $_SESSION['user']->id);
			$query->execute($data);
			$_SESSION['user'] = $query->fetch();
		}
	}
	
	
	
	/**
	* Checks if a user is the currently signed in user
	*/
	
	public static function is_current($userID) {
		static::session_started();
		if ($userID == $_SESSION['user']->id) return true;
		else return false;
	}
	
	
	
	/**
	* Checks if the current user is authorized
	*/
	
	public static function is_authorized() {
		static::session_started();
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
	
	
	
	public static function check_verification() {
		static::session_started();
		# check if the user is verified
		$db = instance(':db');
		$val_sql = instance(':val-sql');
		
		# Check if email is unique
		$query = $db->prepare($val_sql->checkUserVerification);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('id' => $_SESSION['user']->id));
		$row = $query->fetch();
		if ($row->verified) return true;
		
		# check if time limit has expired
		$query = $db->prepare($val_sql->checkUserExpiration);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('id' => $_SESSION['user']->id));
		$row = $query->fetch();
		if ($row->difference >= 2) return false;
		
		return true;
	}
	
	
	
	public static function fullsail_email() {
		static::session_started();
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$query = $db->prepare($sel_sql->getUserFullSailEmailByID);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('id' => $_SESSION['user']->id));
		$row = $query->fetch();
		return $row->email;
	}
	
	
	public static function user_lang() {
		static::session_started();
		return $_SESSION['user']->lang;
	}
	
	
	private static function session_started() {
		if (!static::$_init)
			throw new Exception("Cannot call UserSession methods before calling UserSession::init()");
	}
	
}