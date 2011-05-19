<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class EventExtraInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$exampleDate = date('F j, Y',time() + (7*24*60*60));
		$exampleTime = date('g:00 A',time() + (60*60));
		if (isset($options['data'])) {
			$dateValue = ($options['data']->startDate == '')?'':$options['data']->startDate;
		}
		else {
			$dateValue = $exampleDate;
		}
		
		if (isset($options['data'])) {
			$timeValue = ($options['data']->startTime == '')?'':$options['data']->startTime;
		}
		else {
			$timeValue = $exampleTime;
		}
		
		$durationValue = (isset($options['data']->duration))?$options['data']->duration:2;
		
		return <<<HTML

						<fieldset>
							<label for="location">Where is it at? *</label>
							<input type="text" name="location" id="location" value="{$options['data']->location}" />
						</fieldset>
						<fieldset>
							<label for="date">What day? *</label>
							<input type="text" name="date" id="date" value="{$dateValue}" />
							<small>Example: <strong>$exampleDate</strong></small>
						</fieldset>
						<fieldset>
							<label for="start-time">When does it start? *</label>
							<input type="text" name="start-time" id="start-time" value="{$timeValue}" />
							<small>Example: <strong>$exampleTime</strong></small>
						</fieldset>
						<fieldset>
							<label for="duration">How long will it last?</label>
							<input type="text" name="duration" id="duration" value="{$durationValue}" />
							<small>In <strong>hours</strong>, please</small>
						</fieldset>
HTML;
	}
}