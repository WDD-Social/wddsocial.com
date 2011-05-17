<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class Deleter {
	public static function delete_content_image($file){
		$db = instance(':db');
		$admin = instance(':admin-sql');
		
		$images = array(
			"images/uploads/{$file}_full.jpg",
			"images/uploads/{$file}_large.jpg",
			"images/uploads/{$file}_medium.jpg"
		);
		
		foreach ($images as $image) {
			if (file_exists("$image")) {
				unlink("$image");
			}
		}
		
		$query = $db->prepare($admin->deleteImage);
		$query->execute(array('file' => $file));
	}
}