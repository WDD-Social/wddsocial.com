<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class TeacherDetailInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		return <<<HTML

						<fieldset>
							<label for="graduation-date">Full Sail Graduation Date</label>
							<input type="date" name="graduation-date" id="graduation-date" value="{$options['graduationDateInput']}" />
							<small>YYYY-MM-DD</small>
						</fieldset>
HTML;
	}
}