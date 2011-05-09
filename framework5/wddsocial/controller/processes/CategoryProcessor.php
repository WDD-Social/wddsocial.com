<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class CategoryProcessor {
	public static function add_categories($categories, $contentID, $contentType){
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$admin_sql = instance(':admin-sql');
		
		foreach ($categories as $category) {
			if ($category != '') {
				$data = array('title' => $category);
				$query = $db->prepare($sel_sql->getCategoryByTitle);
				$query->execute($data);
				$query->setFetchMode(\PDO::FETCH_OBJ);
				$result = $query->fetch();
				
				if ($query->rowCount() > 0) {
					$categoryID = $result->id;
				}
				else {
					$data = array('title' => $category);
					$query = $db->prepare($admin_sql->addCategory);
					$query->execute($data);
					
					$categoryID = $db->lastInsertID();
				}
				
				switch ($contentType) {
					case 'project':
						$data = array("projectID" => $contentID, 'categoryID' => $categoryID);
						$query = $db->prepare($admin_sql->addProjectCategory);
						$query->execute($data);
						break;
					case 'article':
						$data = array("articleID" => $contentID, 'categoryID' => $categoryID);
						$query = $db->prepare($admin_sql->addArticleCategory);
						$query->execute($data);
						break;
					case 'event':
						$data = array("eventID" => $contentID, 'categoryID' => $categoryID);
						$query = $db->prepare($admin_sql->addEventCategory);
						$query->execute($data);
						break;
					case 'job':
						$data = array("jobID" => $contentID, 'categoryID' => $categoryID);
						$query = $db->prepare($admin_sql->addJobCategory);
						$query->execute($data);
						break;
				}	
			}
		}
	}
}