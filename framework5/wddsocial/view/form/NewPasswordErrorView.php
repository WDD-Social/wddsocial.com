<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class NewPasswordErrorView implements \Framework5\IView {		
	
	public function render($message = null) {
		
		return <<<HTML
			<p>$message</p>
					
HTML;
	}
}