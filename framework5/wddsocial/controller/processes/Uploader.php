<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class Uploader {
	public static function valid_image($image){
		$type = $image['type'];
		if ($type == 'image/jpeg' or $type == 'image/png' or $type == 'image/gif') {
			return true;
		}
		else {
			return false;
		}
	}
	
	public static function valid_images($images){	
		return true;
		for ($i = 0; $i < count($images['name']); $i++) {
			if ($images['error'][$i] != 4) {
				$type = $images['type'][$i];
				if ($type != 'image/jpeg' or $type != 'image/png' or $type != 'image/gif') {
					return false;
				}
			}
		}
		return true;
	}
	
	public static function valid_image_size($image){
		$size = $image['size']/1024;
		if ($size > 700) {
			return false;
		}
		else {
			return true;
		}
	}
	
	public static function valid_image_sizes($images){
		for ($i = 0; $i < count($images['name']); $i++) {
			if ($images['error'][$i] != 4) {
				$size = $image['size'][$i]/1024;
				if ($size > 700) {
					return false;
				}
			}
		}
		return true;
	}
	
	public static function upload_user_avatar($image, $name){
		$type = mime_content_type($image['tmp_name']);
		if ($type == 'image/jpeg' or $type == 'image/png' or $type == 'image/gif') {
			import('wddsocial.helper.WDDSocial\Resizer');
			$root = \Framework5\Request::root_path();
			$dest = "{$root}images/avatars";
			Resizer::image($image,$name,"_full",$dest,300,500);
			Resizer::image($image,$name,"_medium",$dest,60,60,true);
			Resizer::image($image,$name,"_small",$dest,15,15,true);
			if (file_exists("$dest/$name")) {
				unlink("$dest/$name");
			}
			return true;
		}
		else {
			return false;
		}
	}
	
	public static function upload_employer_avatar($image, $name){
		$type = mime_content_type($image['tmp_name']);
		if ($type == 'image/jpeg' or $type == 'image/png' or $type == 'image/gif') {
			import('wddsocial.helper.WDDSocial\Resizer');
			$root = \Framework5\Request::root_path();
			$dest = "{$root}images/jobs";
			Resizer::image($image,$name,"_full",$dest,300,300);
			Resizer::image($image,$name,"_medium",$dest,60,60,true);
			if (file_exists("$dest/$name")) {
				unlink("$dest/$name");
			}
			return true;
		}
		else {
			return false;
		}
	}
	
	public static function upload_content_images($images, $titles, $contentID, $contentTitle, $contentType){
		$db = instance(':db');
		$admin_sql = instance(':admin-sql');
		$sel_sql = instance(':sel-sql');
		
		$currentUserID = (UserSession::is_authorized())?UserSession::userid():NULL;
		
		for ($i = 0; $i < count($images['name']); $i++) {
			if ($images['error'][$i] != 4) {
				$type = $images['type'][$i];
				if ($type == 'image/jpeg' or $type == 'image/png' or $type == 'image/gif') {
					$imageNumber = $i + 1;
					$imageTitle = ($titles[$i] == '')?"{$contentTitle} | Image $imageNumber":$titles[$i];
					
					$query = $db->prepare($admin_sql->addImage);
					$data = array(	'userID' => $currentUserID,
									'title' => $imageTitle);
					$query->execute($data);
					
					$imageID = $db->lastInsertID();
					
					$query = $db->prepare($sel_sql->getImageFilename);
					$data = array('id' => $imageID);
					$query->execute($data);
					$query->setFetchMode(\PDO::FETCH_OBJ);
					$result = $query->fetch();
					
					switch ($contentType) {
						case 'project':
							$data = array('projectID' => $contentID, 'imageID' => $imageID);
							$query = $db->prepare($admin_sql->addProjectImage);
							break;
						case 'article':
							$data = array('articleID' => $contentID, 'imageID' => $imageID);
							$query = $db->prepare($admin_sql->addArticleImage);
							break;
						case 'event':
							$data = array('eventID' => $contentID, 'imageID' => $imageID);
							$query = $db->prepare($admin_sql->addEventImage);
							break;
						case 'job':
							$data = array('jobID' => $contentID, 'imageID' => $imageID);
							$query = $db->prepare($admin_sql->addJobImage);
							break;
					}
					$query->execute($data);
					
					$newImage = array(	'tmp_name' => $images['tmp_name'][$i],
										'type' => $images['type'][$i]);
					Uploader::upload_image($newImage,"{$result->file}");
				}
				else {
					return false;
				}
			}
		}
		return true;
	}
	
	public static function upload_image($image, $name){
		import('wddsocial.helper.WDDSocial\Resizer');
		$dest = "images/uploads";
		Resizer::image($image,$name,"_full",$dest,800,600);
		Resizer::image($image,$name,"_large",$dest,300,600);
		Resizer::image($image,$name,"_medium",$dest,60,60,true);
		if (file_exists("$dest/$name")) {
			unlink("$dest/$name");
		}
	}
	
	public static function create_ics_file($event){
		$ics = render('wddsocial.view.file.WDDSocial\iCalView', array('section' => 'header'));
		$ics .= render('wddsocial.view.file.WDDSocial\iCalView', array('section' => 'event', 'event' => $event));
		$ics .= render('wddsocial.view.file.WDDSocial\iCalView', array('section' => 'footer'));
		
		if (file_exists("files/ics/wddsocial.{$event->uid}.ics")) {
			unlink("files/ics/wddsocial.{$event->uid}.ics");
		}
		
		$handle = fopen("files/ics/wddsocial.{$event->uid}.ics",'x');
		fwrite($handle,$ics);
		fclose($handle);
	}
}