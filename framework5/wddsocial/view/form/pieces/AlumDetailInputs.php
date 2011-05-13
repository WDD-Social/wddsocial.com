<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class AlumDetailInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		return <<<HTML

						<fieldset>
							<label for="graduation-date">Graduation Date</label>
							<input type="date" name="graduation-date" id="graduation-date" value="{$options['graduationDateInput']}" />
							<small>YYYY-MM-DD</small>
						</fieldset>
						<fieldset>
							<label for="employer">Employer</label>
							<input type="text" name="employer" id="employer" value="{$options['employerTitle']}" />
						</fieldset>
						<fieldset>
							<label for="employer-link">Employer Website</label>
							<input type="text" name="employer-link" id="employer-link" placeholder="example.com" value="{$options['employerLink']}" />
						</fieldset>
HTML;
	}
}