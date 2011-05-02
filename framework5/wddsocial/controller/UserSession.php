<?php

namespace WDDSocial;

/**
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class UserSession {
	
	public static function status() {
		
		session_start();
		
	}
	
	
	
	public static function fake_user_logout() {
		
		$_SESSION['user'] = NULL;
		$_SESSION['authorized'] = false;
		
	}
	
	
	public static function fake_user_login($id){
		
		# get user information
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->prepare($sql->getUserByID);
		import('wddsocial.model.WDDSocial\UserVO');
		$query->setFetchMode(\PDO::FETCH_CLASS, 'WDDSocial\UserVO');
		$data = array('id' => $id);
		$query->execute($data);
		$user = $query->fetch();
		
		# set session
		$_SESSION['user'] = $user;
		$_SESSION['authorized'] = true;
	
	}
}