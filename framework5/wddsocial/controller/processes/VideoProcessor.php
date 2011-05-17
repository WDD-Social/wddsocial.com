<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class VideoProcessor {
	public static function add_videos($videos, $contentID, $contentType){
		import('wddsocial.helper.WDDSocial\HTMLParser');

		$db = instance(':db');
		$val_sql = instance(':val-sql');
		$admin_sql = instance(':admin-sql');
		$parser = new HTMLParser();
		
		foreach ($videos as $video) {
			$parser->load($video);
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
			
			if (stristr($videoSRC,'player.vimeo.com') or stristr($videoSRC,'youtube.com')) {
				$embedCode = "<iframe src=\"$videoSRC\" frameborder=\"0\"$fullscreen></iframe>";
				$query = $db->prepare($val_sql->checkIfVideoExists);
				$query->execute(array('embedCode' => $embedCode));
				$query->setFetchMode(\PDO::FETCH_OBJ);
				$result = $query->fetch();
				if ($query->rowCount() > 0) {
					$videoID = $result->id;
				}
				else {
					$data = array('userID' => $_SESSION['user']->id, 'embedCode' => $embedCode);
					$query = $db->prepare($admin_sql->addVideo);
					$query->execute($data);
					$videoID = $db->lastInsertID();
				}
				
				switch ($contentType) {
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
		}
	}
	
	
	
	public static function update_videos($currentVideos, $newVideos, $contentID, $type){
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$admin_sql = instance(':admin-sql');
		
		foreach ($newVideos as $newVideo) {
			if (in_array($newVideos, $currentVideos)) {
				unset($currentVideos[array_search($newVideo, $currentVideos)]);
				unset($newVideos[array_search($newVideo, $newVideos)]);
			}
		}
		
		if (count($currentVideos) > 0) {
			switch ($type) {
				case 'project':
					foreach ($currentVideos as $currentVideo) {
						$query = $db->prepare($sel_sql->getVideoID);
						$query->execute(array('embedCode' => $currentVideo));
						$query->setFetchMode(\PDO::FETCH_OBJ);
						$result = $query->fetch();
						$videoID = $result->id;
						$query = $db->prepare($admin_sql->deleteProjectVideo);
						$query->execute(array('projectID' => $contentID, 'videoID' => $videoID));
					}
					break;
				case 'article':
					foreach ($currentVideos as $currentVideo) {
						$query = $db->prepare($sel_sql->getVideoID);
						$query->execute(array('embedCode' => $currentVideo));
						$query->setFetchMode(\PDO::FETCH_OBJ);
						$result = $query->fetch();
						$videoID = $result->id;
						$query = $db->prepare($admin_sql->deleteArticleVideo);
						$query->execute(array('articleID' => $contentID, 'videoID' => $videoID));
					}
					break;
				case 'event':
					foreach ($currentVideos as $currentVideo) {
						$query = $db->prepare($sel_sql->getVideoID);
						$query->execute(array('embedCode' => $currentVideo));
						$query->setFetchMode(\PDO::FETCH_OBJ);
						$result = $query->fetch();
						$videoID = $result->id;
						$query = $db->prepare($admin_sql->deleteEventVideo);
						$query->execute(array('eventID' => $contentID, 'videoID' => $videoID));
					}
					break;
				case 'job':
					foreach ($currentVideos as $currentVideo) {
						$query = $db->prepare($sel_sql->getVideoID);
						$query->execute(array('embedCode' => $currentVideo));
						$query->setFetchMode(\PDO::FETCH_OBJ);
						$result = $query->fetch();
						$videoID = $result->id;
						$query = $db->prepare($admin_sql->deleteJobVideo);
						$query->execute(array('jobID' => $contentID, 'videoID' => $videoID));
					}
					break;
			}
		}
		
		static::add_videos($newVideos, $contentID, $type);
	}
}