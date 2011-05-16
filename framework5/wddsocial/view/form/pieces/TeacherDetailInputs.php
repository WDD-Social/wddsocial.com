<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class TeacherDetailInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$graduationDateValue = ($options['graduationDateInput'] == '0000-00-00')?'':$options['graduationDateInput'];
		
		$html .= <<<HTML

						<fieldset>
							<label for="graduation-date">Full Sail Graduation Date</label>
							<p>Are you also an alum? When did you graduate?</p>
							<input type="text" name="graduation-date" id="graduation-date" value="{$graduationDateValue}" />
							<small>YYYY-MM-DD</small>
						</fieldset>
HTML;
		
		$html .= render('wddsocial.view.form.pieces.WDDSocial\CourseInputs', array('courses' => $options['courses']));
		return $html;
	}
}