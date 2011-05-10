<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class VanityURLProcessor {
	public static function generate($id, $type){
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$val_sql = instance(':val-sql');
		$admin_sql = instance(':admin-sql');
		
		$data = array('id' => $id, 'extra' => '');
		switch ($type) {
			case 'project':
				$generateURLquery = $db->prepare($admin_sql->generateProjectVanityURL);
				$getURLquery = $db->prepare($sel_sql->getProjectVanityURL);
				$getURLquery->setFetchMode(\PDO::FETCH_OBJ);
				$checkURLquery = $db->prepare($val_sql->checkIfProjectVanityURLExists);
				$checkURLquery->setFetchMode(\PDO::FETCH_OBJ);
				for ($i = 0; $i < 100; $i++) {
					if ($i > 0) {
						$data = array('id' => $id, 'extra' => "$i");
					}
					$generateURLquery->execute($data);
					
					$getURLquery->execute(array('id' => $id));
					$vanityResult = $getURLquery->fetch();
					$vanityURL = $vanityResult->vanityURL;
					
					$checkURLquery->execute(array('vanityURL' => $vanityURL));
					$checkResult = $checkURLquery->fetch();
					
					if ($checkResult->count > 1) {
						continue;
					}
					else {
						break;
					}
				}
				break;
			case 'article':
				$generateURLquery = $db->prepare($admin_sql->generateArticleVanityURL);
				$getURLquery = $db->prepare($sel_sql->getArticleVanityURL);
				$getURLquery->setFetchMode(\PDO::FETCH_OBJ);
				$checkURLquery = $db->prepare($val_sql->checkIfArticleVanityURLExists);
				$checkURLquery->setFetchMode(\PDO::FETCH_OBJ);
				for ($i = 0; $i < 100; $i++) {
					if ($i > 0) {
						$data = array('id' => $id, 'extra' => "$i");
					}
					$generateURLquery->execute($data);
					
					$getURLquery->execute(array('id' => $id));
					$vanityResult = $getURLquery->fetch();
					$vanityURL = $vanityResult->vanityURL;
					
					$checkURLquery->execute(array('vanityURL' => $vanityURL));
					$checkResult = $checkURLquery->fetch();
					
					if ($checkResult->count > 1) {
						continue;
					}
					else {
						break;
					}
				}
				break;
			case 'event':
				$generateURLquery = $db->prepare($admin_sql->generateEventVanityURL);
				$getURLquery = $db->prepare($sel_sql->getEventVanityURL);
				$getURLquery->setFetchMode(\PDO::FETCH_OBJ);
				$checkURLquery = $db->prepare($val_sql->checkIfEventVanityURLExists);
				$checkURLquery->setFetchMode(\PDO::FETCH_OBJ);
				for ($i = 0; $i < 100; $i++) {
					if ($i > 0) {
						$data = array('id' => $id, 'extra' => "$i");
					}
					$generateURLquery->execute($data);
					
					$getURLquery->execute(array('id' => $id));
					$vanityResult = $getURLquery->fetch();
					$vanityURL = $vanityResult->vanityURL;
					
					$checkURLquery->execute(array('vanityURL' => $vanityURL));
					$checkResult = $checkURLquery->fetch();
					
					if ($checkResult->count > 1) {
						continue;
					}
					else {
						break;
					}
				}
				break;
			case 'job':
				$generateURLquery = $db->prepare($admin_sql->generateJobVanityURL);
				$getURLquery = $db->prepare($sel_sql->getJobVanityURL);
				$getURLquery->setFetchMode(\PDO::FETCH_OBJ);
				$checkURLquery = $db->prepare($val_sql->checkIfJobVanityURLExists);
				$checkURLquery->setFetchMode(\PDO::FETCH_OBJ);
				for ($i = 0; $i < 100; $i++) {
					if ($i > 0) {
						$data = array('id' => $id, 'extra' => "$i");
					}
					$generateURLquery->execute($data);
					
					$getURLquery->execute(array('id' => $id));
					$vanityResult = $getURLquery->fetch();
					$vanityURL = $vanityResult->vanityURL;
					
					$checkURLquery->execute(array('vanityURL' => $vanityURL));
					$checkResult = $checkURLquery->fetch();
					
					if ($checkResult->count > 1) {
						continue;
					}
					else {
						break;
					}
				}
				break;
		}
	}
	
	public static function get($id, $type){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$data = array('id' => $id);
		switch ($type) {
			case 'project':
				$query = $db->prepare($sql->getProjectVanityURL);
				$query->execute($data);
				break;
			case 'article':
				$query = $db->prepare($sql->getArticleVanityURL);
				$query->execute($data);
				break;
			case 'event':
				$query = $db->prepare($sql->getEventVanityURL);
				$query->execute($data);
				break;
			case 'job':
				$query = $db->prepare($sql->getJobVanityURL);
				$query->execute($data);
				break;
		}
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		return $result->vanityURL;
	}
}