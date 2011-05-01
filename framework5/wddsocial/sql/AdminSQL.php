<?php

namespace WDDSocial;

/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class AdminSQL{
	private $_info = array(
		'addUser' => "
			INSERT INTO users
			SET typeID = :typeID, firstName = :firstName, lastName = :lastName, email = :email, fullsailEmail = :fullsailEmail, `password` = MD5(:password), vanityURL = :vanityURL, bio = :bio, hometown = :hometown, birthday = :birthday, `datetime` = NOW()",
		
		'addUserAvatar' => "
			UPDATE users
			SET avatar = MD5(:avatar)
			WHERE id = :id"
	);
	
	public function __get($id){
		return $this->_info[$id];
	}
	
	public function __construct(){
		
	}
	
	public function __destruct(){
		
	}
}