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
		$val_sql = instance(':val-sql');
		
		foreach ($categories as $category) {
			if ($category != '') {
				$categoryID = static::get_categoryID($category);
				
				switch ($contentType) {
					case 'project':
						$data = array('projectID' => $contentID, 'categoryID' => $categoryID);
						$query = $db->prepare($val_sql->checkIfProjectCategoryExists);
						$query->execute($data);
						$query->setFetchMode(\PDO::FETCH_OBJ);
						$result = $query->fetch();
						if ($query->rowCount() == 0) {
							$query = $db->prepare($admin_sql->addProjectCategory);
							$query->execute($data);	
						}
						break;
					case 'article':
						$data = array('articleID' => $contentID, 'categoryID' => $categoryID);
						$query = $db->prepare($val_sql->checkIfArticleCategoryExists);
						$query->execute($data);
						$query->setFetchMode(\PDO::FETCH_OBJ);
						$result = $query->fetch();
						if ($query->rowCount() == 0) {
							$query = $db->prepare($admin_sql->addArticleCategory);
							$query->execute($data);	
						}
						break;
					case 'event':
						$data = array('eventID' => $contentID, 'categoryID' => $categoryID);
						$query = $db->prepare($val_sql->checkIfEventCategoryExists);
						$query->execute($data);
						$query->setFetchMode(\PDO::FETCH_OBJ);
						$result = $query->fetch();
						if ($query->rowCount() == 0) {
							$query = $db->prepare($admin_sql->addEventCategory);
							$query->execute($data);	
						}
						break;
					case 'job':
						$data = array('jobID' => $contentID, 'categoryID' => $categoryID);
						$query = $db->prepare($val_sql->checkIfJobCategoryExists);
						$query->execute($data);
						$query->setFetchMode(\PDO::FETCH_OBJ);
						$result = $query->fetch();
						if ($query->rowCount() == 0) {
							$query = $db->prepare($admin_sql->addJobCategory);
							$query->execute($data);	
						}
						break;
				}
			}
		}
	}
	
	
	
	public static function get_categoryID($category){
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$admin_sql = instance(':admin-sql');
		$val_sql = instance(':val-sql');
		
		$data = array('title' => $category);
		$query = $db->prepare($sel_sql->getCategoryByTitle);
		$query->execute($data);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		
		if ($query->rowCount() > 0) {
			return $result->id;
		}
		else {
			$data = array('title' => $category);
			$query = $db->prepare($admin_sql->addCategory);
			$query->execute($data);
			
			return $db->lastInsertID();
		}
	}
	
	
	
	public static function update_categories($currentCategories, $newCategories, $type, $contentID){
		
		$db = instance(':db');
		$sql = instance(':admin-sql');
		
		foreach ($newCategories as $newCategory) {
			if (in_array($newCategory, $currentCategories)) {
				unset($currentCategories[array_search($newCategory, $currentCategories)]);
				unset($newCategories[array_search($newCategory, $newCategories)]);
			}
		}
		if (count($currentCategories) > 0) {
			switch ($type) {
				case 'project':
					foreach ($currentCategories as $currentCategory) {
						$categoryID = static::get_categoryID($currentCategory);
						$query = $db->prepare($sql->deleteProjectCategory);
						$query->execute(array('projectID' => $contentID, 'categoryID' => $categoryID));
					}
					break;
				case 'article':
					foreach ($currentCategories as $currentCategory) {
						$categoryID = static::get_categoryID($currentCategory);
						$query = $db->prepare($sql->deleteArticleCategory);
						$query->execute(array('articleID' => $contentID, 'categoryID' => $categoryID));
					}
					break;
				case 'event':
					foreach ($currentCategories as $currentCategory) {
						$categoryID = static::get_categoryID($currentCategory);
						$query = $db->prepare($sql->deleteEventCategory);
						$query->execute(array('eventID' => $contentID, 'categoryID' => $categoryID));
					}
					break;
				case 'job':
					foreach ($currentCategories as $currentCategory) {
						$categoryID = static::get_categoryID($currentCategory);
						$query = $db->prepare($sql->deleteJobCategory);
						$query->execute(array('jobID' => $contentID, 'categoryID' => $categoryID	));
					}
					break;
			}
		}
		
		if (count($newCategories) > 0) {
			switch ($type) {
				case 'project':
					foreach ($newCategories as $newCategory) {
						$categoryID = static::get_categoryID($newCategory);
						$query = $db->prepare($sql->addProjectCategory);
						$query->execute(array('projectID' => $contentID, 'categoryID' => $categoryID));
					}
					break;
				case 'article':
					foreach ($newCategories as $newCategory) {
						$categoryID = static::get_categoryID($newCategory);
						$query = $db->prepare($sql->addArticleCategory);
						$query->execute(array('articleID' => $contentID, 'categoryID' => $categoryID));
					}
					break;
				case 'event':
					foreach ($newCategories as $newCategory) {
						$categoryID = static::get_categoryID($newCategory);
						$query = $db->prepare($sql->addEventCategory);
						$query->execute(array('eventID' => $contentID, 'categoryID' => $categoryID));
					}
					break;
				case 'job':
					foreach ($newCategories as $newCategory) {
						$categoryID = static::get_categoryID($newCategory);
						$query = $db->prepare($sql->addJobCategory);
						$query->execute(array('jobID' => $contentID, 'categoryID' => $categoryID));
					}
					break;
			}
		}
	}
}