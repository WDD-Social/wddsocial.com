<?php

namespace WDDSocial;

/**
* 
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class CourseProcessor {
	public static function add_courses($courses, $contentID, $contentType){
		$db = instance(':db');
		$sel_sql = instance(':sel-sql');
		$val_sql = instance(':val-sql');
		$admin_sql = instance(':admin-sql');
		
		$errors = array();
		foreach ($courses as $course) {
			if ($course != '') {
				$data = array('id' => $course, 'title' => $course);
				$query = $db->prepare($sel_sql->getCourse);
				$query->execute($data);
				$query->setFetchMode(\PDO::FETCH_OBJ);
				$result = $query->fetch();
				
				if ($query->rowCount() > 0) {
					$courseID = $result->id;
					switch ($contentType) {
						case 'project':
							$data = array("projectID" => $contentID, 'courseID' => $courseID);
							$query = $db->prepare($val_sql->checkIfProjectCourseExists);
							$query->execute($data);
							$query->setFetchMode(\PDO::FETCH_OBJ);
							$result = $query->fetch();
							if ($query->rowCount() == 0) {
								$query = $db->prepare($admin_sql->addProjectCourse);
								$query->execute($data);
							}
							break;
						case 'article':
							$data = array("articleID" => $contentID, 'courseID' => $courseID);
							$query = $db->prepare($val_sql->checkIfArticleCourseExists);
							$query->execute($data);
							$query->setFetchMode(\PDO::FETCH_OBJ);
							$result = $query->fetch();
							if ($query->rowCount() == 0) {
								$query = $db->prepare($admin_sql->addArticleCourse);
								$query->execute($data);
							}
							break;
						case 'event':
							$data = array("eventID" => $contentID, 'courseID' => $courseID);
							$query = $db->prepare($val_sql->checkIfEventCourseExists);
							$query->execute($data);
							$query->setFetchMode(\PDO::FETCH_OBJ);
							$result = $query->fetch();
							if ($query->rowCount() == 0) {
								$query = $db->prepare($admin_sql->addEventCourse);
								$query->execute($data);
							}
							break;
						case 'job':
							$data = array("jobID" => $contentID, 'courseID' => $courseID);
							$query = $db->prepare($val_sql->checkIfJobCourseExists);
							$query->execute($data);
							$query->setFetchMode(\PDO::FETCH_OBJ);
							$result = $query->fetch();
							if ($query->rowCount() == 0) {
								$query = $db->prepare($admin_sql->addJobCourse);
								$query->execute($data);
							}
							break;
					}
				}
				else if ($course != '') {
					array_push($errors,$course);
				}	
			}
		}
		
		if (count($errors) > 0) {
			$message = 'The course';
			if (count($errors) > 1) {
				$message .= "s ";
				$message .= NaturalLanguage::comma_list($errors);
			}
			else {
				$message .= " {$errors[0]}";
			}
			$message .= " could not be found.";
			$message .= (count($errors) > 1)?' They were':" {$errors[0]} was";
			$message .= " not added to the {$contentType}.";
		}
	}
	
	
	
	public static function update_teacher_courses($userID, $currentCourses, $newCourses){
		$db = instance(':db');
		$sql = instance(':admin-sql');
		
		foreach ($newCourses as $newCourse) {
			if (in_array($newCourse, $currentCourses)) {
				unset($currentCourses[array_search($newCourse, $currentCourses)]);
				unset($newCourses[array_search($newCourse, $newCourses)]);
			}
		}
		if (count($currentCourses) > 0) {
			foreach ($currentCourses as $currentCourse) {
				$query = $db->prepare($sql->deleteTeacherCourse);
				$query->execute(array('userID' => $userID, 'courseID' => $currentCourse));
			}
		}
		
		if (count($newCourses) > 0) {
			foreach ($newCourses as $newCourse) {
				$query = $db->prepare($sql->addTeacherCourse);
				$query->execute(array('userID' => $userID, 'courseID' => $newCourse));
			}
		}
	}
}