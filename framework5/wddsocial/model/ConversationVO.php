<?php

namespace WDDSocial;
/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class ConversationVO{
	
	public $userID, $userName, $userVanityURL, $userAvatar, $unread, $message;
	
	public function __construct(){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$data = array('currentUserID' => UserSession::userid(), 'contactID' => $this->userID);
		
		$query = $db->prepare($sql->getUnreadCount);
		$query->execute($data);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		$this->unread = $result->unreadCount;
		
		$query = $db->prepare($sql->getNewestMessageContent);
		$query->execute($data);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		$this->message = $result->messageContent;
	}
}