<?php

namespace WDDSocial;

/*
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class ConversationsView implements \Framework5\IView {
	
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.user.MessagesPageLang');
	}
	
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {
		$html = <<<HTML
					
					<!--<div class="secondary filters">
						<a href="/messages#all" title="{$this->lang->text('all-conversations-filter')}" class="current">{$this->lang->text('all-filter')}</a> 
						<a href="/messages#unread" title="{$this->lang->text('unread-conversations-filter')}">{$this->lang->text('unread-filter')}</a> 
					</div>-->
					
					<form action="{$_SERVER['REQUEST_URI']}" method="post">
						<p class="error"><strong>{$options['error']}</strong></p>
						<fieldset>
							<label for="to">{$this->lang->text('start-a-convo')}</label>
							<input type="text" name="to" id="to" class="autocompleter" data-autocomplete="users" autocomplete="off" placeholder="{$this->lang->text('convo-with')}" />
							<input type="hidden" name="process" value="start" />
							<small>Press <strong>Enter</strong> to begin conversation</small>
						</fieldset>
					</form>
HTML;
		
		if (set($options['conversations'])) {
			foreach ($options['conversations'] as $conversation) {
				$html.= $this->renderConversation($conversation);
			}
		}
		
		# no messages
		else {
			$html.= "<p class=\"empty\">{$this->lang->text('no-messages')}</p>";
		}
		
		
		return $html;
	}
	
	
	
	private function renderConversation($convo) {
		$userAvatar = (file_exists("images/avatars/{$convo->userAvatar}_medium.jpg"))?"/images/avatars/{$convo->userAvatar}_medium.jpg":"/images/site/user-default_medium.jpg";
		$currentClass = ($convo->userVanityURL == \Framework5\Request::segment(1))?'current':'';
		$unreadClass = ($convo->unread > 0)?'unread':'';
		$badge = ($convo->unread > 0)?"<span class=\"badge\">{$convo->unread}</span>":'';
		return <<<HTML

					<a href="/messages/{$convo->userVanityURL}" title="{$this->lang->text('view-convo-with', $convo->userName)}" class="{$unreadClass} {$currentClass}">
						<img src="{$userAvatar}" alt="{$convo->userName}"/>
						<h2>{$badge}{$convo->userName}</h2>
						<p>{$convo->message}</p>
					</a>
HTML;
	}
}