<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class EventExtraInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		if (isset($options['date'])) {
			$dateValue = ($options['date'] == '0000-00-00')?'':$options['date'];
		}
		else {
			$dateValue = date('Y-m-d',time() + (7*24*60*60));
		}
		
		if (isset($options['time'])) {
			$timeValue = ($options['time'] == '00:00:00')?'':$options['time'];
		}
		else {
			$timeValue = date('H:00',time() + (60*60));
		}
		
		if (isset($options['duration'])) {
			$durationValue = $options['duration'];
		}
		else {
			$durationValue = 2;
		}
		return <<<HTML

						<fieldset>
							<label for="location">Where is it at? *</label>
							<input type="text" name="location" id="location" />
						</fieldset>
						<fieldset>
							<label for="date">What day? *</label>
							<input type="text" name="date" id="date" value="{$dateValue}" />
						</fieldset>
						<fieldset>
							<label for="start-time">When does it start? *</label>
							<input type="time" name="start-time" id="start-time" value="{$timeValue}" />
							<small>Full Sail style, <strong>24-hour</strong> time.</small>
						</fieldset>
						<fieldset>
							<label for="duration">How long will it last?</label>
							<input type="number" name="duration" id="duration" value="{$durationValue}" />
							<small>In <strong>hours</strong>, please</small>
						</fieldset>
HTML;
	}
}