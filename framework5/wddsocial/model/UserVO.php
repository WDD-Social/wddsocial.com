<?php

namespace WDDSocial;
/*
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class UserVO {
	public $id, $language, $firstName, $lastName, $avatar, $bio, $hometown, $age, $type, $contact = array(), $email, $fullsailEmail, $vanityURL, $extra = array();
	
	public function __construct(){
		$this->contact['website']=$this->website;
		$this->contact['twitter']=$this->twitter;
		$this->contact['facebook']=$this->facebook;
		$this->contact['github']=$this->github;
		$this->contact['dribbble']=$this->dribbble;
		$this->contact['forrst']=$this->forrst;
	}
}