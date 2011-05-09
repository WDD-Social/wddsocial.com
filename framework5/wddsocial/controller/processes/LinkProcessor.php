<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class LinkProcessor {
	public static function addLinks($links, $titles, $contentID, $contentType){
		import('wddsocial.helper.WDDSocial\NaturalLanguage');
		
		$db = instance(':db');
		$admin_sql = instance(':admin-sql');
		
		$errors = array();
		foreach ($links as $link) {
			if ($link != '') {
				$i = array_search($link, $links);
				$linkTitle = ($titles[$i] != '')?$titles[$i]:$link;
				$data = array('link' => $link, 'title' => $linkTitle);
				$query = $db->prepare($admin_sql->addLink);
				$query->execute($data);
				
				$linkID = $db->lastInsertID();
				
				switch ($contentType) {
					case 'project':
						$data = array('projectID' => $contentID, 'linkID' => $linkID);
						$query = $db->prepare($admin_sql->addProjectLink);
						$query->execute($data);
						break;
					case 'article':
						$data = array('articleID' => $contentID, 'linkID' => $linkID);
						$query = $db->prepare($admin_sql->addArticleLink);
						$query->execute($data);
						break;
					case 'event':
						$data = array('eventID' => $contentID, 'linkID' => $linkID);
						$query = $db->prepare($admin_sql->addEventLink);
						$query->execute($data);
						break;
					case 'job':
						$data = array('jobID' => $contentID, 'linkID' => $linkID);
						$query = $db->prepare($admin_sql->addJobLink);
						$query->execute($data);
						break;
				}
			}
		}
	}
}