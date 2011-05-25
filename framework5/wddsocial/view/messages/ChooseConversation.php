<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class ChooseConversation implements \Framework5\IView {
	
	public function render($options = null) {
		return <<<HTML

			<p class="empty">Well, you can&rsquo;t talk to anyone if you haven&rsquo;t decided who to talk to yet!</p>
			<p class="empty"><strong>Please select or start a conversation to socialize.</strong></p>
HTML;
	}
}