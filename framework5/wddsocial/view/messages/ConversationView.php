<?php

namespace WDDSocial;

/*
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class ConversationView implements \Framework5\IView {
	
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.user.MessagesPageLang');
		$this->db = instance(':db');
		$this->admin = instance(':admin-sql');
	}
	
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {
		
		# get current user avatar
		$userName = UserSession::user_name();
		$userAvatar = UserSession::user_avatar('medium');
		$userProfile = UserSession::user_profile();
		
		$langViewProfile = $this->lang->text('view-your-profile');
		
		# render reply form
		$html = <<<HTML

					<article>
						<p class="item-image"><a href="{$userProfile}" title="{$langViewProfile}"><img src="{$userAvatar}" alt="{$userName}"/></a></p>
						<form action="{$_SERVER['REQUEST_URI']}" method="post">
							<fieldset>
								<textarea name="message" placeholder="{$this->lang->text('send-user-a-message', $options['user']->firstName)}"></textarea>
								<input type="hidden" name="toID" value="{$options['user']->id}" />
								<input type="hidden" name="process" value="send" />
								<input type="submit" value="Send" />
							</fieldset>
						</form>
					</article>
					
HTML;
		
		# render messages
		if (count($options['messages']) > 0) {
			foreach ($options['messages'] as $message) {
				if ($message->fromUserID == $this->_lastMessageId)
					$html.= $this->renderMessagePlain($message);
				else
					$html.= $this->renderMessageDetails($message);
			}
		}
		else {
			$html.= render('wddsocial.view.messages.WDDSocial\EmptyConversation',array('name' => $options['user']->firstName));
		}
		return $html;
	}
	
	
	
	/**
	* Renders a message with user details and content
	*/
	
	private function renderMessageDetails($message) {
		# save sender id
		$this->_lastMessageId = $message->fromUserID;
		$userAvatar = (file_exists("images/avatars/{$message->fromAvatar}_medium.jpg"))?"/images/avatars/{$message->fromAvatar}_medium.jpg":"/images/site/user-default_medium.jpg";
		$langViewUserProfile = $this->lang->text('view-user-profile', $message->fromUserName);
		$messageContent = nl2br($message->messageContent);
		$linkedMessageContent = Formatter::format_links($messageContent);
		$markAsRead = ($message->messageStatusID == 1 and $message->fromUserID != UserSession::userid())?"<a href=\"/message/read/{$message->messageID}\" title=\"Mark Message As Read\" class=\"markasread\">Mark as Read</a> <span class=\"hidden\">|</span> ":'';
		$html = <<<HTML

					<article>
						<p class="item-image"><a href="{$message->fromVanityURL}" title="{$langViewUserProfile}"><img src="{$userAvatar}" alt="{$message->fromUserName}"/></a></p>
						<h2><a href="{$message->profile}" title="{$langViewUserProfile}">{$message->fromUserName}</a></h2>
						<p>{$linkedMessageContent}</p>
						<p>{$markAsRead}<span class="time">{$message->date}</span></p>
					</article>
HTML;
		return $html;
	}
	
	
	
	/**
	* Renders message with content with timestamp
	*/
	
	private function renderMessagePlain($message) {
		# save sender id
		$this->_lastMessageId = $message->fromUserID;
		$messageContent = nl2br($message->messageContent);
		$linkedMessageContent = Formatter::format_links($messageContent);
		$html = <<<HTML

					<article>
						<p>{$linkedMessageContent}</p>
						<p>{$message->date}</p>
					</article>
HTML;
		return $html;
	}
}