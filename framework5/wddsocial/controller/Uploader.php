<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class Uploader {
	public static function upload_user_avatar($image, $name){
		import('wddsocial.helper.WDDSocial\Resizer');
		$root = \Framework5\Request::root_path();
		$dest = "{$root}images/avatars";
		Resizer::image($image,$name,"_full",$dest,300,500);
		Resizer::image($image,$name,"_medium",$dest,60,60,true);
		Resizer::image($image,$name,"_small",$dest,15,15,true);
		unlink("$dest/$name");
	}
}