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
			
			if (is_array($options['data'])) {
				$locationValue = ($options['data']['location'] == '')?'':$options['data']['location'];
				$dateValue = ($options['data']['date'] == '')?'':$options['data']['date'];
				$timeValue = ($options['data']['start-time'] == '')?'':$options['data']['start-time'];
				$durationValue = (isset($options['data']['duration']))?$options['data']['duration']:2;
			}
			else if (is_object($options['data'])) {
				$locationValue = ($options['data']->location == '')?'':$options['data']->location;
				$dateValue = ($options['data']->startDate == '')?'':$options['data']->startDate;
				$timeValue = ($options['data']->startTime == '')?'':$options['data']->startTime;
				$durationValue = (isset($options['data']->duration))?$options['data']->duration:2;
			}
		}
		else {
			$dateValue = $exampleDate;
			$timeValue = $exampleTime;
		}
		
		return <<<HTML

						<fieldset>
							<label for="location">Where is it at? *</label>
							<input type="text" name="location" id="location" value="{$locationValue}" />
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