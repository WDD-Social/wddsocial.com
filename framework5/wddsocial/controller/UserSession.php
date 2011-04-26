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
		
		if ($_SESSION['authorized']) {
			
		}
			
		static::fake_user();
		
	}
	
	
	
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
	
		/* backup
		$db = instance(':db');
		$sql = new SelectorSQL();
		$query = $db->prepare($sql->getUserByID);
		$query->setFetchMode(\PDO::FETCH_CLASS, 'WDDSocial\UserVO');
		$data = array('id' => 1);
		$query->execute($data);
		$user = $query->fetch();
		
		session_start();
		$_SESSION['user'] = $user;
		$_SESSION['authorized'] = true;
		*/
	}
}