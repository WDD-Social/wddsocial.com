<?php

/*
* WDD Social: Language Pack for 
*/

class MessagesPageLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'page-title':
				return 'Messages';
			
			case 'conversations-header':
				return 'Conversations';
			
			case 'conversation-header':
				return 'Conversation';
			
			case 'view-user-profile':
				return "View {$var}' Profile";
			
			case 'view-your-profile':
				return 'View Your Profile';
			
			case 'send-user-a-message':
				return "Send {$var} a message...";
			
			case 'you';
				return 'You';
			
			case 'load-more':
				return 'Load More';
			
			case 'load-more-title':
				return 'Load more messages...';
			
			case 'no-messages':
				return 'No messages to display';
			
			default:
				throw new Exception("Invalid content '$id'");
		}
	}
}