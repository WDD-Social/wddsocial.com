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
						$query = $db->prepare($admin_sql->addProjectVideo);
						$query->execute($data);
						break;
					case 'article':
						$data = array('articleID' => $contentID, 'videoID' => $videoID);
						$query = $db->prepare($admin_sql->addArticleVideo);
						$query->execute($data);
						break;
					case 'event':
						$data = array('eventID' => $contentID, 'videoID' => $videoID);
						$query = $db->prepare($admin_sql->addEventVideo);
						$query->execute($data);
						break;
					case 'job':
						$data = array('jobID' => $contentID, 'videoID' => $videoID);
						$query = $db->prepare($admin_sql->addJobVideo);
						$query->execute($data);
						break;
				}
			}	
		}
	}
}