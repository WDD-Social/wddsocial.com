<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class LinkProcessor {
	public static function add_links($links, $titles, $contentID, $contentType){
		
		$db = instance(':db');
		$val_sql = instance(':val-sql');
		$admin_sql = instance(':admin-sql');
		
		$errors = array();
		foreach ($links as $link) {
			if (isset($link) and $link != '') {
				$i = array_search($link, $links);
				$linkTitle = ($titles[$i] != '')?$titles[$i]:$link;
				$linkID = static::get_linkID($link, $linkTitle);
				
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
	
	
	
	public static function update_links($currentLinks, $newLinks,  $currentTitles, $newTitles, $contentID, $type){
		$db = instance(':db');
		$sql = instance(':admin-sql');
		
		$currentLinkIDs = array();
		$newLinkIDs = array();
		
		foreach ($currentLinks as $currentLink) {
			$i = array_search($currentLink, $currentLinks);
			$linkTitle = ($currentTitles[$i] != '')?$currentTitles[$i]:$currentLink;
			$linkID = static::get_linkID($currentLink, $linkTitle);
			array_push($currentLinkIDs,$linkID);
		}
		
		foreach ($newLinks as $newLink) {
			$i = array_search($newLink, $newLinks);
			$linkTitle = ($newTitles[$i] != '')?$newTitles[$i]:$newLink;
			$linkID = static::get_linkID($newLink, $linkTitle);
			array_push($newLinkIDs,$linkID);
		}
		
		foreach ($newLinkIDs as $newLinkID) {
			if (in_array($newLinkID, $currentLinkIDs)) {
				unset($currentLinkIDs[array_search($newLinkID, $currentLinkIDs)]);
				unset($newLinkIDs[array_search($newLinkID, $newLinkIDs)]);
			}
		}
		
		if (count($currentLinkIDs) > 0) {
			switch ($type) {
				case 'project':
					foreach ($currentLinkIDs as $currentLinkID) {
						$query = $db->prepare($sql->deleteProjectLink);
						$query->execute(array('projectID' => $contentID, 'linkID' => $currentLinkID));
					}
					break;
				case 'article':
					foreach ($currentLinkIDs as $currentLinkID) {
						$query = $db->prepare($sql->deleteArticleLink);
						$query->execute(array('articleID' => $contentID, 'linkID' => $currentLinkID));
					}
					break;
				case 'event':
					foreach ($currentLinkIDs as $currentLinkID) {
						$query = $db->prepare($sql->deleteEventLink);
						$query->execute(array('eventID' => $contentID, 'linkID' => $currentLinkID));
					}
					break;
				case 'job':
					foreach ($currentLinkIDs as $currentLinkID) {
						$query = $db->prepare($sql->deleteJobLink);
						$query->execute(array('jobID' => $contentID, 'linkID' => $currentLinkID));
					}
					break;
			}
		}
		
		if (count($newLinks) > 0) {
			switch ($type) {
				case 'project':
					foreach ($newLinkIDs as $newLinkID) {
						$query = $db->prepare($sql->addProjectLink);
						$query->execute(array('projectID' => $contentID, 'linkID' => $newLinkID));
					}
					break;
				case 'article':
					foreach ($newLinkIDs as $newLinkID) {
						$query = $db->prepare($sql->addArticleLink);
						$query->execute(array('articleID' => $contentID, 'linkID' => $newLinkID));
					}
					break;
				case 'event':
					foreach ($newLinkIDs as $newLinkID) {
						$query = $db->prepare($sql->addEventLink);
						$query->execute(array('eventID' => $contentID, 'linkID' => $newLinkID));
					}
					break;
				case 'job':
					foreach ($newLinkIDs as $newLinkID) {
						$query = $db->prepare($sql->addJobLink);
						$query->execute(array('jobID' => $contentID, 'linkID' => $newLinkID));
					}
					break;
			}
		}
	}
	
	
	
	public static function get_linkID($link, $linkTitle){
		$db = instance(':db');
		$val_sql = instance(':val-sql');
		$admin_sql = instance(':admin-sql');
		
		$link = strip_tags($link);
		$linkTitle = strip_tags($linkTitle);
		$link = StringCleaner::clean_link($link);
		$data = array('link' => $link, 'title' => $linkTitle);
		$query = $db->prepare($val_sql->checkIfLinkExists);
		$query->execute($data);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		
		if ($query->rowCount() > 0) {
			return $result->id;
		}
		else if(isset($link) and $link != '') {
			$query = $db->prepare($admin_sql->addLink);
			$query->execute($data);
			return $db->lastInsertID();	
		}
	}
}