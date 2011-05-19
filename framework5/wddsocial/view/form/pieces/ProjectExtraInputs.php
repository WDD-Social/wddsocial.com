<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ProjectExtraInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$now = date('F, Y');
		if (isset($options['data'])) {
			$dateValue = ($options['data']->completeDate == '')?'':$options['data']->completeDate;
		}
		else {
			$dateValue = $now;
		}
		return <<<HTML

						<fieldset>
							<label for="completed-date">When was this project completed?</label>
							<input type="text" name="completed-date" id="completed-date" value="{$dateValue}" />
							<small>Example: <strong>$now</strong></small>
						</fieldset>
HTML;
	}
}