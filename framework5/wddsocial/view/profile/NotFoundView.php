<?php

namespace WDDSocial;

/*
* Displayed when the requested user profile does not exist
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class NotFoundView implements \Framework5\IView {
	
	/**
	* Render View
	*/
	
	public static function render($options = null) {
		return "<h1>User Not Found</h1>";
	}
}