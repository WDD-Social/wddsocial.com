<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class CourseInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$randomLimit = (isset($options['courses']))?count($options['courses']) + 2:2;
		$query = $db->query($sql->getRandomCourses . " LIMIT $randomLimit");
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$courses = $query->fetchAll();
		
		if ($options['header'] == true) {
			$html = <<<HTML

						<h1 id="courses">Courses</h1>
						<p>Does this {$_POST['type']} relate to any courses?</p>
						<fieldset>
HTML;
		}
		else {
			$html .= <<<HTML

						<fieldset>
							<label>Courses</label>
HTML;
		}
		$i = 1;
		if (isset($options['courses'])) {
			foreach ($options['courses'] as $course) {
				$html .= <<<HTML

							<input type="text" name="courses[]" class="autocompleter" data-autocomplete="courses" autocomplete="off" placeholder="{$courses[$i-1]->id}" value="{$course->id}" />
HTML;
				$i++;
			}
		}
		for ($i; $i < $randomLimit; $i++) {
			$html .= <<<HTML

							<input type="text" name="courses[]" class="autocompleter" data-autocomplete="courses" autocomplete="off" placeholder="{$courses[$i-1]->id}" />
HTML;
		}
		$html .= <<<HTML

						</fieldset>
						<a href="" title="Add Another Course" class="add-more">Add Another Course</a>
HTML;
		return $html;
	}
}