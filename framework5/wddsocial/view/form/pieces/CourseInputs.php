<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class CourseInputs implements \Framework5\IView {		
	
	public static function render($options = null) {
		$html = <<<HTML

						<h1 id="courses">Courses</h1>
						<p>Does this {$_POST['type']} relate to any of your courses?</p>
						<fieldset>
HTML;
		for ($i = 1; $i < 3; $i++) {
			$html .= <<<HTML

							<input type="text" name="courses[]" id="course$i" />
HTML;
		}
		$html .= <<<HTML

							<a href="#" title="Add Another Course" class="add-more">Add Another Course</a>
						</fieldset>
HTML;
		return $html;
	}
}