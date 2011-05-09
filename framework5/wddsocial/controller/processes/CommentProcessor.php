<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class CommentProcessor {
	public static function add_comment($comment, $contentID, $contentType){
		$db = instance(':db');
		$sql = instance(':admin-sql');
		
		$data = array('userID' => $_SESSION['user']->id, 'content' => $comment);
		$query = $db->prepare($sql->addComment);
		$query->execute($data);
		
		$commentID = $db->lastInsertID();
		
		switch ($contentType) {
			case 'project':
				$data = array('projectID' => $contentID, 'commentID' => $commentID);
				$query = $db->prepare($sql->addProjectComment);
				$query->execute($data);
				break;
			case 'article':
				$data = array('articleID' => $contentID, 'commentID' => $commentID);
				$query = $db->prepare($sql->addArticleComment);
				$query->execute($data);
				break;
			case 'event':
				$data = array('eventID' => $contentID, 'commentID' => $commentID);
				$query = $db->prepare($sql->addEventComment);
				$query->execute($data);
				break;
		}
	}
}