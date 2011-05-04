<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ProjectDetails implements \Framework5\IView {		
	
	public static function render($options = null) {
		$root = \Framework5\Request::root_path();
		return <<<HTML

						<h1>Project Details</h1>
						<fieldset>
							<label for="completed-date">When was this project completed?</label>
							<input type="date" name="completed-date" id="completed-date" />
						</fieldset>
HTML;
	}
}