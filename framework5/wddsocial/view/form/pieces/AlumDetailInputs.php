<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class AlumDetailInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$now = date('F, Y');
		return <<<HTML

						<fieldset>
							<label for="graduation-date">Graduation Date</label>
							<input type="text" name="graduation-date" id="graduation-date" value="{$options['graduationDate']}" />
							<small>Example: <strong>$now</strong></small>
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