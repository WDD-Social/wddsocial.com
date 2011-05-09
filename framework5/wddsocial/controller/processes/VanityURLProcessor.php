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
		$sql = instance(':admin-sql');
		
		$data = array('id' => $id);
		switch ($type) {
			case 'project':
				$query = $db->prepare($sql->generateProjectVanityURL);
				$query->execute($data);
				break;
			case 'article':
				$query = $db->prepare($sql->generateArticleVanityURL);
				$query->execute($data);
				break;
			case 'event':
				$query = $db->prepare($sql->generateEventVanityURL);
				$query->execute($data);
				break;
			case 'job':
				$query = $db->prepare($sql->generateJobVanityURL);
				$query->execute($data);
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