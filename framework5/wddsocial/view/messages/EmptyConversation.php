<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class EmptyConversation implements \Framework5\IView {
	
	public function render($options = null) {
		return <<<HTML

			<p class="empty">Hm, kinda quiet in here, don&rsquo;t you think?</p>
			<p class="empty"><strong>Why don&rsquo;t you say something to {$options['name']}?</strong></p>
HTML;
	}
}