<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class TeacherDetailInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$now = date('F, Y');
		
		$html .= <<<HTML

						<fieldset>
							<label for="graduation-date">Did you graduate from Full Sail? If so, when?</label>
							<input type="text" name="graduation-date" id="graduation-date" value="{$options['graduationDate']}" />
							<small>Example: <strong>$now</strong></small>
						</fieldset>
HTML;
		
		$html .= render('wddsocial.view.form.pieces.WDDSocial\CourseInputs', array('courses' => $options['courses']));
		return $html;
	}
}