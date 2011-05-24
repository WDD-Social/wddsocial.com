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
				return 'Looks like you have no conversations going on. Enter a friend&rsquo;s name in the field above to begin.';
			
			
			# ConversationsView
			case 'all-filter':
				return 'All';
			
			case 'all-conversations-filter':
				return 'All Conversations';
			
			case 'unread-filter':
				return 'Unread';
			
			case 'unread-conversations-filter';
				return 'Unread Conversations';
			
			case 'view-convo-with':
				return "View Conversation with {$var}";
			
			case 'start-a-convo':
				return 'Start a Conversation';
			
			case 'convo-with':
				return 'With...';
			
			
			default:
				throw new Exception("Invalid content '$id'");
		}
	}
}