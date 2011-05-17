<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class TeamMemberProcessor {
	public static function add_team_members($members, $contentID, $contentType, $titles = array()){
		
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$val_sql = instance(':val-sql');
		$admin_sql = instance(':admin-sql');
		
		$errors = array();
		foreach ($members as $member) {
			$i = array_search($member, $members);
			$userID = static::get_userID($member);
			
			if ($userID) {
				switch ($contentType) {
					case 'project':
						$data = array('userID' => $userID, 'projectID' => $contentID);
						$query = $db->prepare($val_sql->checkIfProjectTeamMemberExists);
						$query->execute($data);
						$query->setFetchMode(\PDO::FETCH_OBJ);
						$result = $query->fetch();
						if ($query->rowCount() == 0) {
							$data = array('userID' => $userID, 'projectID' => $contentID, 'title' => $titles[$i]);
							$query = $db->prepare($admin_sql->addProjectTeamMember);
							$query->execute($data);	
						}
						break;
					case 'article':
						$data = array('userID' => $userID, 'articleID' => $contentID);
						$query = $db->prepare($val_sql->checkIfArticleAuthorExists);
						$query->execute($data);
						$query->setFetchMode(\PDO::FETCH_OBJ);
						$result = $query->fetch();
						if ($query->rowCount() == 0) {
							$query = $db->prepare($admin_sql->addArticleAuthor);
							$query->execute($data);	
						}
						break;
				}
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
	
	
	
	public static function update_team_members($currentMembers, $newMembers, $contentID, $type, $currentTitles = array(), $newTitles = array()){
		$db = instance(':db');
		$sql = instance(':admin-sql');
		
		foreach ($newMembers as $newMember) {
			if (in_array($newMember, $currentMembers)) {
				$currentKey = array_search($newMember, $currentMembers);
				$newKey = array_search($newMember, $newMembers);
				unset($currentMembers[$currentKey]);
				unset($newMembers[$newKey]);
				if ($type == 'project')
					$userID = static::get_userID($newMember);
					if ($newTitles[$newKey] != $currentTitles[$currentKey]) {
						$query = $db->prepare($sql->updateProjectTeamMemberRole);
						$query->execute(array('projectID' => $contentID, 'userID' => $userID, 'title' => $newTitles[$newKey]));
					}
					unset($currentTitles[$currentKey]);
					unset($newTitles[$newKey]);
			}
		}
		
		if (count($currentMembers) > 0) {
			switch ($type) {
				case 'project':
					foreach ($currentMembers as $currentMember) {
						$key = array_search($currentMember, $currentMembers);
						$userID = static::get_userID($currentMember);
						$query = $db->prepare($sql->deleteProjectTeamMember);
						$query->execute(array('projectID' => $contentID, 'userID' => $userID));
					}
					break;
				case 'article':
					foreach ($currentMembers as $currentMember) {
						$userID = static::get_userID($currentMember);
						$query = $db->prepare($sql->deleteArticleAuthor);
						$query->execute(array('articleID' => $contentID, 'userID' => $userID));
					}
					break;
			}
		}
		
		if (count($newMembers) > 0) {
			switch ($type) {
				case 'project':
					foreach ($newMembers as $newMember) {
						$key = array_search($newMember, $newMembers);
						$userID = static::get_userID($newMember);
						$query = $db->prepare($sql->addProjectTeamMember);
						$query->execute(array('projectID' => $contentID, 'userID' => $userID, 'title' => $newTitles[$key]));
					}
					break;
				case 'article':
					foreach ($newMembers as $newMember) {
						$userID = static::get_userID($newMember);
						$query = $db->prepare($sql->addArticleAuthor);
						$query->execute(array('articleID' => $contentID, 'userID' => $userID));
					}
					break;
			}
		}
	}
	
	
	
	public static function get_userID($name){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$data = array('name' => $name);
		$query = $db->prepare($sql->getUserByName);
		$query->execute($data);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		if ($query->rowCount() > 0)
			return $result->id;
		else
			return false;
	}
}