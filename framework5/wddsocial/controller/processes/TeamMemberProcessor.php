<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class TeamMemberProcessor {
	public static function addTeamMembers($members, $contentID, $contentType, $titles = array()){
		import('wddsocial.helper.WDDSocial\NaturalLanguage');
		
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$admin_sql = instance(':admin-sql');
		
		$errors = array();
		foreach ($members as $member) {
			$i = array_search($member, $members);
			$data = array('name' => $member);
			$query = $db->prepare($sel_sql->getUserByName);
			$query->execute($data);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$result = $query->fetch();
			
			if ($query->rowCount() > 0) {
				switch ($contentType) {
					case 'project':
						$data = array('userID' => $result->id, 'projectID' => $contentID, 'title' => $titles[$i]);
						$query = $db->prepare($admin_sql->addProjectTeamMember);
						break;
					case 'article':
						$data = array('userID' => $result->id, 'articleID' => $contentID);
						$query = $db->prepare($admin_sql->addArticleAuthor);
						break;
				}
				$query->execute($data);
			}
			else if ($member != '') {
				array_push($errors,$member);
			}
		}
		
		if (count($errors) > 0) {
			$message = 'The user';
			if (count($errors) > 1) {
				$message .= "s ";
				$message .= NaturalLanguage::comma_list($errors);
			}
			else {
				$message .= " {$errors[0]}";
			}
			$message .= " could not be found.";
			$message .= (count($errors) > 1)?' They were':" {$errors[0]} was";
			$message .= " not added to the {$contentType}.";
		}
	}
}