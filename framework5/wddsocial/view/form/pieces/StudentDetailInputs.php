<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class StudentDetailInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$campusSelected = ($options['location'] == 'on-campus')?' checked':'';
		$onlineSelected = ($options['location'] == 'online')?' checked':'';
		$startDateValue = ($options['startDateInput'] == '0000-00-00')?'':$options['startDateInput'];
		return <<<HTML

						<fieldset>
							<label for="start-date">Start Date</label>
							<input type="text" name="start-date" id="start-date" value="{$startDateValue}" />
							<small>YYYY-MM-DD</small>
						</fieldset>
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
	}
}