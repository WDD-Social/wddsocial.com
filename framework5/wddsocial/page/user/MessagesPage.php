<?php

namespace WDDSocial;

/*
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class MessagesPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.user.MessagesPageLang');
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		$this->admin = instance(':admin-sql');
	}
	
	
	
	public function execute() {
		
		# require authentication to access this page
		UserSession::protect();
		
		if (isset($_POST['process'])) {
			switch ($_POST['process']) {
				case 'start':
					if ($_POST['to'] != '') {
						$query = $this->db->prepare($this->sql->getUserByName);
						$query->execute(array('name' => $_POST['to']));
						if ($query->rowCount() > 0) {
							$query->setFetchMode(\PDO::FETCH_OBJ);
							$result = $query->fetch();
							$vanityURL = $result->vanityURL;
							$redirectURL = "/messages/$vanityURL";
							redirect("$redirectURL");
						}
						else {
							$conversationsError = 'Uh oh, that user could not be found. Please try again.';
						}
					}
					break;
				case 'send':
					$message = strip_tags($_POST['message']);
					if ($_POST['toID'] != '' and $message != '') {
						$query = $this->db->prepare($this->admin->sendMessage);
						$query->execute(array('content' => $message, 'fromID' => UserSession::userid(), 'toID' => $_POST['toID']));
					}
					break;
			}
		}
		
		$contactVanityURL = \Framework5\Request::segment(1);
		if (isset($contactVanityURL) and $contactVanityURL != '') {
			import('wddsocial.model.WDDSocial\UserVO');
			$data = array('vanityURL' => $contactVanityURL);
			$query = $this->db->prepare($this->sql->getUserByVanityURL);
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
			$query->execute($data);
			$contact = $query->fetch();
		}
		else {
			$contact = false;
		}
		
		# get contacts
		$conversations = array();
		import('wddsocial.model.WDDSocial\ConversationVO');
		$query = $this->db->prepare($this->sql->getConversations);
		$query->execute(array('id' => UserSession::userid()));
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ConversationVO');
		while ($item = $query->fetch()) {
			array_push($conversations,$item);
		}
		
		$messages = array();
		# get messages
		if ($contact) {
			$query = $this->db->prepare($this->sql->getConversation);
			$query->execute(array('currentUserID' => UserSession::userid(), 'contactID' => $contact->id));
			$query->setFetchMode(\PDO::FETCH_OBJ);
			while ($msg = $query->fetch()) {
				array_push($messages,$msg);
			}
		}
		
		# begin content
		$content = render(':section', array('section' => 'begin_content', 'classes' => array('messages')));
		
		# conversations
		$content.= render(':section', 
			array('section' => 'begin_content_section',
			'id' => 'conversations',
			'classes' => array('small', 'with-secondary', 'filterable'),
			'header' => $this->lang->text('conversations-header'))
		);
		
		$content.= render('wddsocial.view.messages.WDDSocial\ConversationsView', array('conversations' => $conversations, 'error' => $conversationsError));
		
		$content.= render(':section', array('section' => 'end_content_section'));
		
		# selected conversation
		$content.= render(':section',
			array('section' => 'begin_content_section',
			'id' => 'conversation', 
			'classes' => array('medium', 'no-margin'),
			'header' => $this->lang->text('conversation-header', "{$contact->firstName} {$contact->lastName}"))
		);
		
		if (!$contact) {
			$content .= render('wddsocial.view.messages.WDDSocial\ChooseConversation');
		}
		else {
			$content .= render('wddsocial.view.messages.WDDSocial\ConversationView', array('user' => $contact, 'messages' => $messages));
		}
		
		$content.= render(':section', array('section' => 'end_content_section'));
		
		# end content
		$content.= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', array('title' => $this->lang->text('page-title'), 'content' => $content));
	}
}