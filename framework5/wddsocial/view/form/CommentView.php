<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class CommentView implements \Framework5\IView {		
	
	public function render($options = null) {
		return <<<HTML

						<form action="{$_SERVER['REQUEST_URI']}" method="post">
							<p class="error"><strong>{$options['error']}</strong></p>
							<textarea name="content" placeholder="Write your comment..."></textarea>
							<input type="submit" name="submit" value="Post Comment" />
						</form>
HTML;
	}
}