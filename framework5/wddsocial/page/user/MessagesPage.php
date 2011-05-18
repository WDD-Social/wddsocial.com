<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class MessagesPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.user.MessagesPageLang');
	}
	
	
	
	public function execute() {
		
		# require authentication to access this page
		UserSession::protect();
		
		
		$msg = new \stdClass();
		$msg->sender_id = 5;
		$msg->sender = 'Alicia Brooks';
		$msg->timestamp = '10 minutes ago';
		$msg->content = 'Holisticly engage multimedia based metrics with robust partnerships.';
		$msg->avatar = '/images/avatars/7e58d63b60197ceb55a1c487989a3720_medium.jpg';
		$msg->profile = '/user/tyler';
		$messages = array($msg, $msg, $msg);
		
		
		# begin content
		$content = render(':section', array('section' => 'begin_content', 
			'classes' => array('messages')));
		
		
		
		# conversations
		$content.= render(':section',
			array('section' => 'begin_content_section', 'id' => 'conversations', 
				'classes' => array('small', 'with-secondary', 'filterable'),
				'header' => $this->lang->text('conversations-header')));
		
		$content.= render('wddsocial.view.messages.WDDSocial\ConversationsView');
		
		$content.= render(':section', array('section' => 'end_content_section'));
		
		
		
		# selected conversation
		$content.= render(':section',
			array('section' => 'begin_content_section', 'id' => 'conversation', 
				'classes' => array('medium', 'no-margin'),
				'header' => $this->lang->text('conversation-header')));
		
		$content.= render('wddsocial.view.messages.WDDSocial\ConversationView',
			array('user' => 'Alicia', 'messages' => $messages));
		
		$content.= render(':section', array('section' => 'end_content_section'));
		
		
		
		# end content
		$content.= render(':section', array('section' => 'end_content_section'));
		$content.= render(':section', array('section' => 'end_content'));
		
		
		
		# display page
		echo render('wddsocial.view.global.WDDSocial\SiteTemplate', 
			array('title' => $this->lang->text('page-title'), 'content' => $content));

		
	}
}