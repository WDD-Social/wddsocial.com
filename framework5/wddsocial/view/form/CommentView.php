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

						<form action="{$root}" method="post">
							<textarea class="placeholder">Write your comment...</textarea>
							<input type="submit" value="Post Comment" />
						</form>
HTML;
	}
}