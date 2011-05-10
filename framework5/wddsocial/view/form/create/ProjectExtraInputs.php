<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ProjectExtraInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$today = date('Y-m-d');
		return <<<HTML

						<fieldset>
							<label for="completed-date">When was this project completed?</label>
							<input type="date" name="completed-date" id="completed-date" value="$today" />
							<small>yyyy-mm-dd</small>
						</fieldset>
HTML;
	}
}