<?php

namespace WDDSocial;

/*
* 
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class ConversationView implements \Framework5\IView {
	
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.user.MessagesPageLang');
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
						<h2><a href="{$userProfile}" title="{$langViewProfile}">{$this->lang->text('you')}</a></h2>
						<form action="" method="post">
							<fieldset>
								<input type="text" name="to" id="to" placeholder="{$this->lang->text('send-user-a-message', $options['user'])}" />
							</fieldset>
						</form>
					</article>
					
HTML;
		
		# render messages
		if (set($options['messages'])) {
			foreach ($options['messages'] as $message) {
				if ($message->sender_id == $this->_lastMessageId)
					$html.= $this->renderMessagePlain($message);
				else
					$html.= $this->renderMessageDetails($message);
			}
		}
		
		# no messages
		else {
			$html.= "<p>{$this->lang->text('no-messages')}</p>";
		}
		
		# load more
		$html.= <<<HTML
					
					<p class="load-more"><a href="#" title="{$this->lang->text('load-more-title')}">{$this->lang->text('load-more')}</a></p>
HTML;
		
		return $html;
	}
	
	
	
	/**
	* Renders a message with user details and content
	*/
	
	private function renderMessageDetails($message) {
		# save sender id
		$this->_lastMessageId = $message->sender_id;
		$langViewUserProfile = $this->lang->text('view-user-profile', $message->sender);
		$html = <<<HTML
					<article>
						<p class="item-image"><a href="{$message->profile}" title="{$langViewUserProfile}"><img src="{$message->avatar}" alt="{$message->sender}"/></a></p>
						<h2><a href="{$message->profile}" title="{$langViewUserProfile}">{$message->sender}</a></h2>
						<p>{$message->content}</p>
						<p>{$message->timestamp}</p>
					</article>
HTML;
		return $html;
	}
	
	
	
	/**
	* Renders message with content with timestamp
	*/
	
	private function renderMessagePlain($message) {
		# save sender id
		$this->_lastMessageId = $message->sender_id;
		
		$html = <<<HTML

					<article>
						<p>{$message->content}</p>
						<p>{$message->timestamp}</p>
					</article>
HTML;
		return $html;
	}
}