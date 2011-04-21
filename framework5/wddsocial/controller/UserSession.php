<?php

namespace WDDSocial;

/**
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class UserSession {
	
	public static function status() {
	
		import('wddsocial.model.UserVO');
		
		# get user information
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->prepare($sql->getUserByID);
		$query->setFetchMode(\PDO::FETCH_CLASS, 'WDDSocial\UserVO');
		$data = array('id' => 1);
		$query->execute($data);
		$user = $query->fetch();
		
		# set session
		session_start();
		$_SESSION['user'] = $user;
		$_SESSION['authorized'] = false;
	}
	
		/* backup
		import('wddsocial.model.UserVO');
		
		# 
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