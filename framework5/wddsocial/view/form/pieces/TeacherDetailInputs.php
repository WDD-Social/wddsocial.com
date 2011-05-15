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
		
		$randomLimit = count($options['courses']) + 3;
		$query = $db->query($sql->getRandomCourses . " LIMIT $randomLimit");
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$courses = $query->fetchAll();
		
		$html .= <<<HTML

						<fieldset>
							<label for="graduation-date">Full Sail Graduation Date</label>
							<input type="text" name="graduation-date" id="graduation-date" value="{$options['graduationDateInput']}" />
							<small>YYYY-MM-DD</small>
						</fieldset>
HTML;
		
		$html .= <<<HTML

						<fieldset>
							<label for="course1">Courses</label>
HTML;
		$i = 1;
		foreach ($options['courses'] as $course) {
			$html .= <<<HTML

							<input type="text" name="courses[]" id="course$i" placeholder="{$courses[$i-1]->id}" value="{$course->id}" />
HTML;
			$i++;
		}
		for ($i; $i < $randomLimit; $i++) {
			$html .= <<<HTML

							<input type="text" name="courses[]" id="course$i" placeholder="{$courses[$i-1]->id}" />
HTML;
		}
		$html .= <<<HTML

						</fieldset>
						<a href="#" title="Add Another Course" class="add-more">Add Another Course</a>
HTML;
		return $html;
	}
}