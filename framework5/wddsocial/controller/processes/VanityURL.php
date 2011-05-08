<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class VanityURL {
	public static function generate($id, $type){
		$db = instance(':db');
		$sql = instance(':admin-sql');
		
		$data = array('id' => $id);
		switch ($type) {
			case 'project':
				$query = $db->prepare($sql->generateProjectVanityURL);
				break;
			case 'article':
				$query = $db->prepare($sql->generateArticleVanityURL);
				break;
			case 'event':
				$query = $db->prepare($sql->generateEventVanityURL);
				break;
			case 'job':
				$query = $db->prepare($sql->generateJobVanityURL);
				break;
		}
		$query->execute($data);
	}
	
	public static function get($id, $type){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$data = array('id' => $id);
		switch ($type) {
			case 'project':
				$query = $db->prepare($sql->getProjectVanityURL);
				break;
			case 'article':
				$query = $db->prepare($sql->getArticleVanityURL);
				break;
			case 'event':
				$query = $db->prepare($sql->getEventVanityURL);
				break;
			case 'job':
				$query = $db->prepare($sql->getJobVanityURL);
				break;
		}
		$query->execute($data);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		return $result->vanityURL;
	}
}