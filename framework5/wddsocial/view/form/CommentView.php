<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class CommentView implements \Framework5\IView {		
	
	public function render($options = null) {
		$root = \Framework5\Request::root_path();
		return <<<HTML

						<form action="{$_SERVER['REQUEST_URI']}" method="post">
							<p class="error"><strong>{$options['error']}</strong></p>
							<textarea name="content" class="placeholder">Write your comment...</textarea>
							<input type="submit" name="submit" value="Post Comment" />
						</form>
HTML;
	}
}