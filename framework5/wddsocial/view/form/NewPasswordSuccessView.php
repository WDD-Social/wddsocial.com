<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class NewPasswordSuccessView implements \Framework5\IView {		
	
	public function render($message = null) {
		
		return <<<HTML

			<p>$message</p>
HTML;
	}
}