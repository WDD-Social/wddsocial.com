<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class VideoProcessor {
	public static function add_videos($videos, $contentID, $contentType){
		foreach ($videos as $video) {
			$embedCode = static::clean_embed($video);
			if (stristr($embedCode,'player.vimeo.com') or stristr($embedCode,'youtube.com'))
				$videoID = static::get_videoID($embedCode);
				static::add_video($videoID, $contentID, $contentType);
		}
	}
	
	
	
	public static function update_videos($currentVideos, $newVideos, $contentID, $type){
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$admin_sql = instance(':admin-sql');
		
		echo "<pre>";
		print_r($currentVideos);
		print_r($newVideos);
		echo "</pre>";
		
		foreach ($newVideos as $newVideo) {
			if (in_array($newVideo, $currentVideos) or $newVideo == '') {
				unset($currentVideos[array_search($newVideo, $currentVideos)]);
				unset($newVideos[array_search($newVideo, $newVideos)]);
			}
		}
		
		echo "<pre>";
		print_r($currentVideos);
		print_r($newVideos);
		echo "</pre>";
		
		if (count($newVideos) > 0) {
			foreach ($newVideos as $nv) {
				$cleanEmbed = static::clean_embed(htmlspecialchars_decode($nv));
				$nvID = static::get_videoID($cleanEmbed);
				static::add_video($nvID, $contentID, $type);
			}			
		}
		
		if (count($currentVideos) > 0) {
			foreach ($currentVideos as $cv) {
				$cleanEmbed = static::clean_embed(htmlspecialchars_decode($cv));
				$cvID = static::get_videoID($cleanEmbed);
				static::delete_video($cvID, $contentID, $type);
			}			
		}
	}
	
	
	
	public static function clean_embed($rawEmbed){
		import('wddsocial.helper.WDDSocial\HTMLParser');
		
		$parser = new HTMLParser();
		$parser->load($rawEmbed);
		$iframe = $parser->find('iframe');
		
		if (stristr($iframe[0]->attr['src'], '?'))
			$videoSRC = stristr($iframe[0]->attr['src'], '?', true);
		else
			$videoSRC = $iframe[0]->attr['src'];
		
		if (stristr($videoSRC,'player.vimeo.com')) {
			$videoSRC .= "?color=74b336";
			$fullscreen = '';
		}
		else if (stristr($videoSRC,'youtube.com')) {
			$fullscreen = ' allowfullscreen';
		}
		$embedCode = "<iframe src=\"$videoSRC\" frameborder=\"0\"$fullscreen></iframe>";
		return $embedCode;
	}
	
	
	
	public static function get_videoID($embedCode){
		$db = instance(':db');
		$val_sql = instance(':val-sql');
		$admin_sql = instance(':admin-sql');
		
		$query = $db->prepare($val_sql->checkIfVideoExists);
		$query->execute(array('embedCode' => $embedCode));
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		if ($query->rowCount() > 0) {
			return $result->id;
		}
		else {
			$data = array('userID' => UserSession::userid(), 'embedCode' => $embedCode);
			$query = $db->prepare($admin_sql->addVideo);
			$query->execute($data);
			return $db->lastInsertID();
		}
	}
	
	
	
	public static function add_video($videoID, $contentID, $type){
		$db = instance(':db');
		$val_sql = instance(':val-sql');
		$admin_sql = instance(':admin-sql');
		
		switch ($type) {
			case 'project':
				$data = array('projectID' => $contentID, 'videoID' => $videoID);
				$query = $db->prepare($val_sql->checkIfProjectVideoExists);
				$query->execute($data);
				if ($query->rowCount() == 0) {
					$query = $db->prepare($admin_sql->addProjectVideo);
					$query->execute($data);
				}
				break;
			case 'article':
				$data = array('articleID' => $contentID, 'videoID' => $videoID);
				$query = $db->prepare($val_sql->checkIfArticleVideoExists);
				$query->execute($data);
				if ($query->rowCount() == 0) {
					$query = $db->prepare($admin_sql->addArticleVideo);
					$query->execute($data);
				}
				break;
			case 'event':
				$data = array('eventID' => $contentID, 'videoID' => $videoID);
				$query = $db->prepare($val_sql->checkIfEventVideoExists);
				$query->execute($data);
				if ($query->rowCount() == 0) {
					$query = $db->prepare($admin_sql->addEventVideo);
					$query->execute($data);
				}
				break;
			case 'job':
				$data = array('jobID' => $contentID, 'videoID' => $videoID);
				$query = $db->prepare($val_sql->checkIfJobVideoExists);
				$query->execute($data);
				if ($query->rowCount() == 0) {
					$query = $db->prepare($admin_sql->addJobVideo);
					$query->execute($data);
				}
				break;
		}
	}
	
	
	
	public static function delete_video($videoID, $contentID, $type){
		$db = instance(':db');
		$admin_sql = instance(':admin-sql');
		
		switch ($type) {
			case 'project':
				$query = $db->prepare($admin_sql->deleteProjectVideo);
				$query->execute(array('projectID' => $contentID, 'videoID' => $videoID));
				break;
			case 'article':
				$query = $db->prepare($admin_sql->deleteArticleVideo);
				$query->execute(array('articleID' => $contentID, 'videoID' => $videoID));
				break;
			case 'event':
				$query = $db->prepare($admin_sql->deleteEventVideo);
				$query->execute(array('eventID' => $contentID, 'videoID' => $videoID));
				break;
			case 'job':
				$query = $db->prepare($admin_sql->deleteJobVideo);
				$query->execute(array('jobID' => $contentID, 'videoID' => $videoID));
				break;
		}
	}
}