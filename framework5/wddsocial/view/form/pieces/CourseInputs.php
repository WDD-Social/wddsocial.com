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
		
		$query = $db->query($sql->getThreeRandomCourses);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$courses = $query->fetchAll();
		
		$html = <<<HTML

						<h1 id="courses">Courses</h1>
						<p>Does this {$_POST['type']} relate to any of your courses?</p>
						<fieldset>
HTML;
		for ($i = 1; $i < 3; $i++) {
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