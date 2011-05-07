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
	
	public static function upload_employer_avatar($image, $name){
		import('wddsocial.helper.WDDSocial\Resizer');
		$root = \Framework5\Request::root_path();
		$dest = "{$root}images/jobs";
		Resizer::image($image,$name,"_full",$dest,300,300);
		Resizer::image($image,$name,"_medium",$dest,60,60,true);
		unlink("$dest/$name");
	}
	
	public static function upload_image($image, $name){
		import('wddsocial.helper.WDDSocial\Resizer');
		$root = \Framework5\Request::root_path();
		$dest = "{$root}images/uploads";
		Resizer::image($image,$name,"_full",$dest,800,600);
		Resizer::image($image,$name,"_large",$dest,300,250);
		Resizer::image($image,$name,"_medium",$dest,60,60,true);
		unlink("$dest/$name");
	}
	
	public static function clean_video_tag($embedCode){
		echo "<h1>$embedCode</h1>";
	}
	
	public static function create_ics_file($event){
		echo "<pre>";
		echo render('wddsocial.view.WDDSocial\iCalView', array('section' => 'header'));
		echo render('wddsocial.view.WDDSocial\iCalView', array('section' => 'event', 'event' => $event));
		echo render('wddsocial.view.WDDSocial\iCalView', array('section' => 'footer'));
		echo "</pre>";
		
		/* TO DISPLAY:
		import('wddsocial.controller.WDDSocial\Uploader');
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$data = array('id' => 1);
		$query = $db->prepare($sql->getEventICSValues);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute($data);
		$event = $query->fetch();
		Uploader::create_ics_file($event);
		*/
	}
}