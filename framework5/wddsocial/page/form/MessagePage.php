<?php

namespace WDDSocial;

/*
* 
* Author: Anthony Colangelo (me@acolangelo.com)
*/

class MessagePage implements \Framework5\IExecutable {
	
	public function execute() {
		UserSession::protect();
		
		$this->db = instance(':db');
		$this->admin = instance(':admin-sql');
		$this->val = instance(':val-sql');
		
		$action = \Framework5\Request::segment(1);
		
		switch ($action) {
			case 'read':
				$messageID = \Framework5\Request::segment(2);
				$query = $this->db->prepare($this->val->checkIfUserCanMarkMessage);
				$query->execute(array('userID' => UserSession::userid(), 'messageID' => $messageID));
				if ($query->rowCount() > 0) {
					$newquery = $this->db->prepare($this->admin->markMessageAsRead);
					$newquery->execute(array('id' => $messageID));
				}
				redirect("/{$_SESSION['last_request']}");
				break;
		}
	}
}