<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class UserDetailInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		if (isset($options['startDate'])) {
			$html .= <<<HTML

						<fieldset>
							<label for="start-date">Start Date</label>
							<input type="date" name="start-date" id="start-date" value="{$options['startDateInput']}" />
							<small>yyyy-mm-dd</small>
						</fieldset>
HTML;
		}
		if (isset($options['graduationDate'])) {
			$html .= <<<HTML

						<fieldset>
							<label for="graduation-date">Graduation Date</label>
							<input type="date" name="graduation-date" id="graduation-date" value="{$options['graduationDateInput']}" />
							<small>yyyy-mm-dd</small>
						</fieldset>
HTML;
		}
		$campusSelected = ($options['location'] == 'on-campus')?' checked':'';
		$onlineSelected = ($options['location'] == 'online')?' checked':'';
		$html .= <<<HTML

						<fieldset class="radio">
							<label for="degree-location">Degree Location</label>
							<div>
								<input type="radio" id="on-campus" name="degree-location" value="on-campus"{$campusSelected} />
								<label for="on-campus">On-Campus</label>
								
								<input type="radio" id="online" name="degree-location" value="online"{$onlineSelected} />
								<label for="online">Online</label>
							</div>
						</fieldset>
HTML;
		if (isset($options['employerTitle']) or isset($options['employerLink'])) {
			$html .= <<<HTML

						<fieldset>
							<label for="employer">Employer</label>
							<input type="text" name="employer" id="employer" value="{$options['employerTitle']}" />
						</fieldset>
						<fieldset>
							<label for="employer-link">Employer Website</label>
							<input type="text" name="employer-link" id="employer-link" value="{$options['employerLink']}" />
							<small>@username</small>
						</fieldset>
HTML;
		}
		return $html;
	}
}