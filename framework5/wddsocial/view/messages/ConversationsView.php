<?php

namespace WDDSocial;

/*
* 
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class ConversationsView implements \Framework5\IView {
	
	
	public function __construct() {
		//$this->lang = new \Framework5\Lang('wddsocial.lang.view.global.ViewLang');
	}
	
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {
		$html = <<<HTML
					
					<div class="secondary filters">
						<a href="messages.html#all" title="All Conversations" class="current">All</a> 
						<a href="messages.html#unread" title="Unread Conversations">Unread</a> 
					</div><!-- END SECONDARY -->
					
					<form action="" method="post">
						<fieldset>
							<label for="to">Start a Conversation</label>
							<input type="text" name="to" id="to" placeholder="With..." />
						</fieldset>
					</form>
HTML;

		$html.= <<<HTML
					<a href="#tyler" title="View Conversation with Tyler Matthews" class="unread">
						<img src="images/avatars/tyler_small.jpg" alt="Tyler Matthews"/>
						<h2><span class="badge">2</span> Tyler Matthews</h2>
						<p>Authoritatively pursue open-source e-markets before enabled leadership. Rapidiously evisculate technically sound ideas rather than functional users.</p>
					</a>
HTML;

		return $html;
	}
}