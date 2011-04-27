<?php

namespace WDDSocial;
/*
* 
* 
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class ContentVO {
	public $id, $userID, $title, $type, $description, $content, $categories = array(), $links = array(), $team = array(), $images = array(), $videos = array(), $comments = array();
	
	public function __construct(){
		
	}
	
}