<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class LikesDislikesProcessor {
	public static function update_likes($userID, $currentLikes, $newLikes){
		$db = instance(':db');
		$sql = instance(':admin-sql');
		
		foreach ($newLikes as $newLike) {
			if (in_array($newLike, $currentLikes)) {
				unset($currentLikes[array_search($newLike, $currentLikes)]);
				unset($newLikes[array_search($newLike, $newLikes)]);
			}
		}
		if (count($currentLikes) > 0) {
			foreach ($currentLikes as $currentLike) {
				$query = $db->prepare($sql->deleteUserLike);
				$categoryID = static::get_categoryID($currentLike);
				$query->execute(array('userID' => $userID, 'categoryID' => $categoryID));
			}
		}
		
		if (count($newLikes) > 0) {
			foreach ($newLikes as $newLike) {
				$query = $db->prepare($sql->addUserLike);
				$categoryID = static::get_categoryID($newLike);
				$query->execute(array('userID' => $userID, 'categoryID' => $categoryID));
			}
		}
	}
	
	
	
	public static function update_dislikes($userID, $currentDislikes, $newDislikes){
		$db = instance(':db');
		$sql = instance(':admin-sql');
		
		foreach ($newDislikes as $newDislike) {
			if (in_array($newDislike, $currentDislikes)) {
				unset($currentDislikes[array_search($newDislike, $currentDislikes)]);
				unset($newDislikes[array_search($newDislike, $newDislikes)]);
			}
		}
		if (count($currentDislikes) > 0) {
			foreach ($currentDislikes as $currentDislike) {
				$query = $db->prepare($sql->deleteUserDislike);
				$categoryID = static::get_categoryID($currentDislike);
				$query->execute(array('userID' => $userID, 'categoryID' => $categoryID));
			}
		}
		
		if (count($newDislikes) > 0) {
			foreach ($newDislikes as $newDislike) {
				$query = $db->prepare($sql->addUserDislike);
				$categoryID = static::get_categoryID($newDislike);
				$query->execute(array('userID' => $userID, 'categoryID' => $categoryID));
			}
		}
	}
	
	
	
	private static function get_categoryID($title){
		$db = instance(':db');
		$sel = instance(':sel-sql');
		$admin = instance(':admin-sql');
		
		$query = $db->prepare($sel->getCategoryIDByTitle);
		$query->execute(array('title' => $title));
		
		if ($query->rowCount() > 0) {
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$result = $query->fetch();
			return $result->id;
		}
		else {
			$query = $db->prepare($admin->addCategory);
			$query->execute(array('title' => $title));
			return $db->lastInsertID();
		}
	}
}