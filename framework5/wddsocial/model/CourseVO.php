<?php

namespace WDDSocial;
/*
*
* Author: Anthony Colangelo (me@acolangelo.com)
*
*/
class CourseVO{
	
	public $id, $title, $description, $month, $type = 'course', $categories = array(), $team = array();
	
	public function __construct(){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$data = array('id' => $this->id);
		
		$query = $db->prepare($sql->getCourseCategories);
		$query->execute($data);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		while ($category = $query->fetch()) {
			array_push($this->categories, $category);
		}
		
		$query = $db->prepare($sql->getCourseTeachers);
		$query->execute($data);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		while ($teacher = $query->fetch()) {
			array_push($this->team, $teacher);
		}
	}
}