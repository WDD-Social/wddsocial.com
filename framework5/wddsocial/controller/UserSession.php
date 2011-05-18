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
	* Protects a page from an unauthorized user
	*/
	
	public static function protect() {
		if (!static::is_authorized()) {
			$_SESSION['last_page'] = \Framework5\Request::uri();
			redirect('/signin');
		}
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
	* Get the current users id
	*/
	
	public static function userid() {
		static::session_started();
		if (!static::is_authorized()) return false;
		return $_SESSION['user']->id;
	}
	
	
	
	/**
	* Get the current users avatar location
	*/
	
	public static function user_avatar($size) {
		static::session_started();
		
		$valid = array('small', 'medium', 'large');
		if (!in_array($size, $valid))
			throw new Exception("Invalid user avatar size '{$size}'");
		
		$dir = 'images/avatars/';
		$size = "_{$size}.jpg";
		$file = $dir . $_SESSION['user']->avatar . $size;
		
		if (file_exists($file)) return "/$file";	
		else return "/images/site/user-default{$size}";
	}
	
	

	/**
	* Get the current users full name
	*/
	
	public static function user_name() {
		static::session_started();
		return "{$_SESSION['user']->firstName} {$_SESSION['user']->lastName}";
	}
	
	
	
	/**
	* Get the current users profile url
	*/
	
	public static function user_profile() {
		static::session_started();
		return "/user/{$_SESSION['user']->vanityURL}";
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
	* Checks if the current user has flagged a piece of content
	*/
	
	public static function has_flagged($id, $type) {
		$db = instance(':db');
		$sql = instance(':val-sql');
		
		$data = array('id' => $id, 'userID' => static::userid());
		switch ($type) {
			case 'project':
				$query = $db->prepare($sql->checkIfProjectHasBeenFlagged);
				break;
			case 'article':
				$query = $db->prepare($sql->checkIfArticleHasBeenFlagged);
				break;
			case 'event':
				$query = $db->prepare($sql->checkIfEventHasBeenFlagged);
				break;
			case 'job':
				$query = $db->prepare($sql->checkIfJobHasBeenFlagged);
				break;
			case 'comment':
				$query = $db->prepare($sql->checkIfCommentHasBeenFlagged);
				break;
		}
		$query->execute($data);
		if ($query->rowCount() > 0)
			return true;
		else
			return false;
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
		if ($row->verified == 1) return true;
		
		# check if time limit has expired
		$query = $db->prepare($val_sql->checkUserExpiration);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute(array('id' => $_SESSION['user']->id));
		$row = $query->fetch();
		if ($row->difference < 2) return true;
		
		return false;
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