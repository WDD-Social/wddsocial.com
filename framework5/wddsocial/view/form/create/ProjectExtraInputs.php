<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ProjectExtraInputs implements \Framework5\IView {		
	
	public static function render($options = null) {
		return <<<HTML

						<fieldset>
							<label for="completed-date">When was this project completed?</label>
							<input type="date" name="completed-date" id="completed-date" />
						</fieldset>
HTML;
	}
}