<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class EventExtraInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$date = date('Y-m-d',time() + (7*24*60*60));
		$time = date('H:00',time() + (60*60));
		return <<<HTML

						<fieldset>
							<label for="location">Where is it at? *</label>
							<input type="text" name="location" id="location" />
						</fieldset>
						<fieldset>
							<label for="date">What day? *</label>
							<input type="date" name="date" id="date" value="$date" />
						</fieldset>
						<fieldset>
							<label for="start-time">When does it start? *</label>
							<input type="time" name="start-time" id="start-time" value="$time" />
							<small>Full Sail style, <strong>24-hour</strong> time.</small>
						</fieldset>
						<fieldset>
							<label for="duration">How long will it last?</label>
							<input type="number" name="duration" id="duration" value="2" />
							<small>In <strong>hours</strong>, please</small>
						</fieldset>
HTML;
	}
}