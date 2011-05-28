<?php

namespace WDDSocial;

/*
* User Messages Page
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class MessagesPage implements \Framework5\IExecutable {
	
	/**
	* Include dependencies
	*/
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.user.MessagesPageLang');
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		$this->admin = instance(':admin-sql');
	}
	
	
	
	public function execute() {
		
		# require authentication to access this page
		UserSession::protect();
		
		# handle form submission
		if (isset($_POST['process'])) {
			switch ($_POST['process']) {
				case 'start':
					if (!empty($_POST['to'])) {
						$query = $this->db->prepare($this->sql->getUserByName);
						$query->execute(array('name' => $_POST['to']));
						if ($query->rowCount() > 0) {
							$query->setFetchMode(\PDO::FETCH_OBJ);
							$result = $query->fetch();
							redirect("/messages/{$result->vanityURL}");
						}
						else {
							$conversationsError = 'Uh oh, that user could not be found. Please try again.';
						}
					}
					break;
				
				case 'send':
					$message = strip_tags($_POST['message']);
					if ($_POST['toID'] != '' and !empty($message)) {
						$query = $this->db->prepare($this->admin->sendMessage);
						$query->execute(array('content' => $message, 'fromID' => UserSession::userid(), 'toID' => $_POST['toID']));
					}
					break;
			}
		}
		
		
		# conversation selected
		$conversationUser = \Framework5\Request::segment(1);
		
		# redirect user to /messages if they try to message themselves
		$query = $this->db->prepare($this->sql->getUserIDByVanityURL);
		$query->execute(array('vanityURL' => $conversationUser));
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		if (UserSession::userid() == $result->id) redirect('/messages');
		
		if (isset($conversationUser) and !empty($conversationUser)) {
			import('wddsocial.model.WDDSocial\UserVO');
			$data = array('vanityURL' => $conversationUser);
			$query = $this->db->prepare($this->sql->getUserByVanityURL);
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
			$query->execute($data);
			$contact = $query->fetch();
		}
		
		else {
			$contact = false;
		}
		
		# list conversations
		$query = $this->db->prepare($this->sql->getConversations);
		$query->execute(array('id' => UserSession::userid()));
		import('wddsocial.model.WDDSocial\ConversationVO');
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ConversationVO');
		
		$conversations = array();
		while ($item = $query->fetch()) {
			array_push($conversations, $item);
		}
		
		
		# get messages
		if ($contact) {
			$query = $this->db->prepare($this->sql->getConversation);
			$query->execute(
				array('currentUserID' => UserSession::userid(), 'contactID' => $contact->id));
			$query->setFetchMode(\PDO::FETCH_OBJ);
			
			$messages = array();
			while ($msg = $query->fetch()) {
				array_push($messages, $msg);
			}
		}
		
		# begin content
		$content = render(':section', 
			array('section' => 'begin_content', 'classes' => array('messages')));
		
		# conversations
		$content.= render(':section', 
			array('section' => 'begin_content_section',
			'id' => 'conversations',
			'classes' => array('small', 'with-secondary', 'filterable'),
			'header' => $this->lang->text('conversations-header'))
		);
		
		$content.= render('wddsocial.view.messages.WDDSocial\ConversationsView', 
			array('conversations' => $conversations, 'error' => $conversationsError));
		
		$content.= render(':section', array('section' => 'end_content_section'));
		
		# selected conversation
		$content.= render(':section',
			array('section' => 'begin_content_section',
			'id' => 'conversation', 
			'classes' => array('medium', 'no-margin'),
			'header' => $this->lang->text(
				'conversation-header', "{$contact->firstName} {$contact->lastName}"))
		);
		
		
		
		if ($contact) {
			$content .= render('wddsocial.view.messages.WDDSocial\ConversationView', 
				array('user' => $contact, 'messages' => $messages));
		}
		
		else {
			$content .= render('wddsocial.view.messages.WDDSocial\ChooseConversation');
		}
		
		
		$content.= render(':section', array('section' => 'end_content_section'));
		$content.= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => $this->lang->text('page-title'), 'content' => $content));
	}
}